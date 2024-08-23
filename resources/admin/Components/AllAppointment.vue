<template>
    <div class="cancel-appointment-container">
      <!-- Header with navigation button -->
      <div class="header">
        <el-button type="primary" @click="navigateToPatientList">Go to Patient List</el-button>
      </div>
  
      <!-- Filters: Search by Contact Number and Date Picker -->
      <div class="filters">
        <el-input
          v-model="searchQuery"
          placeholder="Search by Contact Number"
          class="search-input"
          @input="fetchAppointments"
          clearable
        ></el-input>
  
        <el-date-picker
          v-model="filterDate"
          type="date"
          placeholder="Filter by Date"
          @change="fetchAppointments"
        ></el-date-picker>
      </div>
  
      <!-- Appointments Table -->
      <el-table :data="appointments" style="width: 100%; margin-top: 20px;">
        <el-table-column prop="patient.name" label="Patient Name"></el-table-column>
        <el-table-column prop="patient.contact_info" label="Contact Number"></el-table-column>
        <el-table-column prop="date" label="Date"></el-table-column>
        <el-table-column prop="start_time" label="Start Time"></el-table-column>
        <el-table-column prop="end_time" label="End Time"></el-table-column>
        <el-table-column label="Actions">
          <template v-slot="scope">
            <el-button type="danger" size="mini" @click="cancelAppointment(scope.row.id)">Cancel</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </template>
  
  <script>
  export default {
    name: 'AllAppointment',
    data() {
      return {
        doctorId: this.$route.params.id,
        appointments: [],
        searchQuery: '',
        filterDate: null,
      };
    },
    methods: {
      fetchAppointments() {
        let formattedDate = null;
        if (this.filterDate) {
      const year = this.filterDate.getFullYear();
      const month = String(this.filterDate.getMonth() + 1).padStart(2, '0');
      const day = String(this.filterDate.getDate()).padStart(2, '0');
      formattedDate = `${year}-${month}-${day}`;
    }
        this.$get(`doctors/${this.doctorId}/appointments`,{
          contact_number: this.searchQuery,
          filter_date: formattedDate
        })
          .then(response => {
            console.log(formattedDate);
            this.appointments = response.appointments;
          })
          .catch(error => {
            this.$notify.error('There was an error fetching the appointments.');
            console.error(error);
          });
      },
      cancelAppointment(appointmentId) {
        this.$del(`doctors/${this.doctorId}/appointments/${appointmentId}`)
        .then(response => {
                this.$notify.success(response.message || 'Doctor deleted successfully');
                this.fetchAppointments();
            })
            .catch(error => {
                this.$handleError(error);
                console.log(error);
            });
      },
      navigateToPatientList() {
        this.$router.push({ name: 'patient-management', params: { id: this.doctorId } });
      }
    },
    mounted() {
      this.fetchAppointments();
    }
  };
  </script>
  
  <style lang="scss" scoped>
  .cancel-appointment-container {
    padding: 20px;
  
    .header {
      display: flex;
      justify-content: flex-start;
      margin-bottom: 20px;
    }

    
  
    .filters {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
  
      .search-input {
        flex-grow: 1;
        max-width: 300px;
        margin-right: 20px;
      }
  
      .el-date-picker {
        max-width: 200px;
      }
    }
  
    .el-table {
      width: 100%;
    }
  
    .el-button {
      margin-right: 10px;
    }
  }
  </style>
  