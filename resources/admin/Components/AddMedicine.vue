<template>
    <div class="add-medicine-container">
      <el-card class="add-medicine-card" shadow="hover">
        <h2 class="card-title">Add New Medicine</h2>
        <el-form :model="medicine" label-width="120px" class="add-medicine-form">
          <el-form-item label="Medicine Name" :rules="[{ required: true, message: 'Please input Medicine Name', trigger: 'blur' }]">
            <el-input v-model="medicine.name" placeholder="e.g. Napa"></el-input>
          </el-form-item>
  
          <el-form-item label="Generic Name" :rules="[{ required: true, message: 'Please input Generic Name', trigger: 'blur' }]">
            <el-input v-model="medicine.generic_name" placeholder="e.g. Paracetamol"></el-input>
          </el-form-item>
  
          <el-form-item label="Brand Name">
            <el-input v-model="medicine.brand_name" placeholder="e.g. Beximco Pharmaceuticals Ltd."></el-input>
          </el-form-item>
  
          <el-form-item label="Dosage Form" :rules="[{ required: true, message: 'Please input Dosage Form', trigger: 'blur' }]">
            <el-input v-model="medicine.dosage_form" placeholder="e.g. Tablet"></el-input>
          </el-form-item>
  
          <el-form-item label="Strength" :rules="[{ required: true, message: 'Please input Strength', trigger: 'blur' }]">
            <el-input v-model="medicine.strength" placeholder="e.g. 500mg"></el-input>
          </el-form-item>
  
          <el-form-item class="form-actions">
            <el-button type="primary" @click="submitForm">Add Medicine</el-button>
            <el-button @click="cancelForm">Cancel</el-button>
          </el-form-item>
        </el-form>
      </el-card>
    </div>
  </template>
  
  <script>
  export default {
    name: 'AddMedicine',
    data() {
      return {
        doctorId: this.$route.params.id,
        medicine: {
          name: '',
          generic_name: '',
          brand_name: '',
          dosage_form: '',
          strength: '',
        },
        saving: false
      };
    },
    methods: {
      submitForm() {
        this.saving = true;
          this.$post(`doctors/${this.doctorId}/medicine`, {
            medicine:this.medicine,
          })
            .then(response => {
              this.$notify.success('Medicine added successfully!');
              this.$router.push({ name: 'viewMedicines' , params: { id: this.doctorId }});
            })
            .catch(error => {
                this.$handleError(errors);
                console.log(errors);
            })
            .finally(() => {
                this.saving = false;
            });
      },
      cancelForm() {
        this.$router.push({ name: 'patient-management', params: { id: this.doctorId } });
      }
    }
  };
  </script>
  
  <style lang="scss" scoped>
  .add-medicine-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
  }
  
  .add-medicine-card {
    width: 100%;
    max-width: 600px;
    margin-top: 20px;
    padding: 20px;
  }
  
  .card-title {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
  }
  
  .add-medicine-form {
    width: 100%;
  }
  
  .form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
  }
  </style>
  