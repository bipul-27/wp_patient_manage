<template>
    <div class="medicine-management-container">
      <div class="header">
        <el-button type="primary" @click="navigateToPatientList">Go to Patient List</el-button>
        <el-button type="success" @click="navigateToAddMedicine">Add Medicine</el-button>
      </div>
      <div class="search-bar">
        <el-input
          v-model="searchQuery"
          placeholder="Search by Medicine Name"
          class="search-input"
          @input="fetchMedicines"
          clearable
        ></el-input>
      </div>
      <el-table :data="filteredMedicines" style="width: 100%; margin-top: 20px;">
        <el-table-column prop="name" label="Medicine Name"></el-table-column>
        <el-table-column prop="generic_name" label="Generic Name"></el-table-column>
        <el-table-column prop="brand_name" label="Brand Name"></el-table-column>
        <el-table-column prop="dosage_form" label="Dosage Form"></el-table-column>
        <el-table-column prop="strength" label="Strength"></el-table-column>
      </el-table>
    </div>
  </template>
  
  <script>
  export default {
    name: 'ViewMedicines',
    data() {
      return {
        doctorId: this.$route.params.id,
        medicines: [],
        searchQuery: '',
        saving: false
      };
    },
    computed: {
      filteredMedicines() {
        return this.medicines
          .filter(medicine =>
            medicine.name.toLowerCase().includes(this.searchQuery.toLowerCase())
          )
          .sort((a, b) => a.name.localeCompare(b.name));
      }
    },
    methods: {
      fetchMedicines() {
        this.saving = true;
        this.$get(`doctors/${this.doctorId}/medicine`,{
            search: this.searchQuery
        })
          .then(response => {
            this.medicines = response.medicines;
          })
          .catch(error => {
            this.$handleError(errors);
                    console.log(errors);
          })
          .finally(() => {
                 this.saving = false;
            });

      },
      navigateToPatientList() {
        this.$router.push({ name: 'patient-management', params: { id: this.doctorId } });
      },
      navigateToAddMedicine() {
        this.$router.push({ name: 'add-medicine', params: { id: this.doctorId } });
      }
    },
    mounted() {
      this.fetchMedicines();
    }
  };
  </script>
  
  <style lang="scss" scoped>
  .medicine-management-container {
    padding: 20px;
  
    .header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
  
      .el-button {
        margin-right: 10px;
      }
    }
  
    .search-bar {
      margin-bottom: 20px;
  
      .search-input {
        width: 100%;
        max-width: 300px;
      }
    }
  }
  </style>
  