<template>
  <div class="patient-management-container">
    <aside class="sidebar">
      <nav class="nav">
        <ul>
          <li @click="toggleMenu('patient')" :class="{ expanded: showPatientMenu }">
            Patient
            <ul v-if="showPatientMenu" class="submenu">
              <li @click="setActiveTab('all-patients')">All Patients</li>
              <li @click="navigateToCreatePatient">Add Patient</li>
            </ul>
          </li>
          <li @click="toggleMenu('appointment')" :class="{ expanded: showAppointmentMenu }">
            Appointment
            <ul v-if="showAppointmentMenu" class="submenu">
              <li @click="makeAppointment">Make Appointment</li>
              <li @click="allAppointment">All Appointment</li>
            </ul>
          </li>

          <li @click="toggleMenu('medicine')" :class="{ expanded: showMedicineMenu }">
            Medicine
            <ul v-if="showMedicineMenu" class="submenu">
              <li @click="viewMedicines">View Medicines</li>
              <li @click="addMedicine">Add Medicine</li>
            </ul>
          </li>
        </ul>
      </nav>
      <el-button type="text" class="back-button" @click="navigateBack">Back to Doctor List</el-button>
    </aside>

    <main class="content">
      <div v-if="activeTab === 'all-patients'" class="patient-list">
        <div class="actions">
          <el-input
            v-model="searchQuery"
            placeholder="Search by Contact Number"
            class="search-input"
            @input="fetchPatients"
            clearable
          ></el-input>
          
          <el-select v-model="healthConditionFilter" placeholder="Filter by Health Condition" @change="fetchPatients">
            <el-option label="All" value=""></el-option>
            <el-option label="Critical" value="critical"></el-option>
            <el-option label="Stable" value="stable"></el-option>
            <el-option label="Normal" value="normal"></el-option>
          </el-select>
        </div>

        <el-table :data="patients" style="width: 100%; margin-top: 20px;">
          <el-table-column prop="name" label="Name"></el-table-column>
          <el-table-column prop="email" label="Email"></el-table-column>
          <el-table-column prop="age" label="Age"></el-table-column>
          <el-table-column prop="gender" label="Gender"></el-table-column>
          <el-table-column prop="contact_info" label="Contact Number"></el-table-column>
          <el-table-column prop="health_condition" label="Health Condition"></el-table-column>
          <el-table-column label="Actions">
            <template v-slot="scope">
              <el-button type="primary" size="mini" @click="editPatient(scope.row)">Edit</el-button>
              <el-button type="success" size="mini" @click="viewPatientDetails(scope.row)">Details</el-button>
              <el-button type="danger" size="mini" @click="confirmDeletePatient(scope.row.id)">Delete</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>

      <div v-else-if="activeTab === 'add-patient'">
      </div>
    </main>
  </div>
</template>

<script>
export default {
  name: 'PatientManagement',
  data() {
    return {
      doctorId: this.$route.params.id,
      healthConditionFilter: '',
      searchQuery: '',
      patients: [],
      activeTab: 'all-patients',
      showPatientMenu: false,
      showAppointmentMenu: false,
      showMedicineMenu: false,
    };
  },
  methods: {
    navigateBack() {
      this.$router.push({ name: 'dashboard' });
    },
    navigateToCreatePatient() {
      this.setActiveTab('add-patient');
      this.$router.push({ name: 'create-patient', params: { id: this.doctorId } });
    },
    fetchPatients() {
      this.$get(`doctors/${this.doctorId}/patients`, {
        health_condition: this.healthConditionFilter,
        query: this.searchQuery  
      })
      .then(response => {
        this.patients = response.patients;
      })
      .catch(error => {
        this.$handleError(error);
        console.log(error);
      });
    },
    confirmDeletePatient(id) {
            this.$confirm('Are you sure you want to delete this patient?', 'Warning', {
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                type: 'warning'
            }).then(() => {
                this.deletePatient(id);
            }).catch(() => {
                console.log('Deletion canceled');
            });
        },
    deletePatient(id) {
      this.$del(`doctors/${this.doctorId}/patients/${id}`)
      .then(response => {
        this.$notify.success(response.message || 'Patient deleted successfully');
        this.fetchPatients();
      })
      .catch(error => {
        this.$handleError(error);
        console.log(error);
      });
    },
    editPatient(patient) {
      this.$router.push({ name: 'edit-patient', params: { doctorId: this.doctorId, patientId: patient.id } });
    },
    viewPatientDetails(patient) {
      this.$router.push({ name: 'patient-details', params: { doctorId: this.doctorId, patientId: patient.id } });
    },
    setActiveTab(tab) {
      this.activeTab = tab;
    },
    makeAppointment() {
      this.$router.push({ name: 'makeAppointment' , params: { id: this.doctorId }});
    },
    allAppointment() {
      this.$router.push({ name: 'allAppointment' , params: {id: this.doctorId}});
    },
    viewMedicines() {
      this.$router.push({ name: 'viewMedicines' , params: { id: this.doctorId }});
    },
    addMedicine() {
  this.$router.push({ name: 'add-medicine' , params: { id: this.doctorId }});
}
,
    toggleMenu(menu) {
      if (menu === 'patient') {
        this.showPatientMenu = !this.showPatientMenu;
        this.showAppointmentMenu = false;
        this.showMedicineMenu = false;
      } else if (menu === 'appointment') {
        this.showAppointmentMenu = !this.showAppointmentMenu;
        this.showPatientMenu = false;
        this.showMedicineMenu = false;
      } else if (menu === 'medicine') {
        this.showMedicineMenu = !this.showMedicineMenu;
        this.showPatientMenu = false;
        this.showAppointmentMenu = false;
      }
    }
  },
  mounted() {
    this.fetchPatients();
  }
};
</script>

<style lang="scss" scoped>


.patient-management-container {
  display: flex;
  height: 100vh;

  .sidebar {
    width: 250px;
    background-color: #0e3633;
    color: #fff;
    padding: 50px 0px;
    /* flex-direction: column; */
    /* justify-content: space-between; */
    align-items: center;
    height: fit-content;
    border-radius: 25px;
    margin-top: 50px;
    

    .nav {
      ul {
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 18px;

        li {
          padding: 15px;
          cursor: pointer;
          position: relative;
          text-align: left;

          &.active {
            background-color: #1abc9c;

          }

          &:hover {
            background-color: transparent;
          }

          .submenu {
            display: none;
            position: relative;
            background-color: #2c3e50;
            margin-top: 5px;
            padding: 0;
            border-radius: 5px;

            li {
              padding: 8px;
              cursor: pointer;
              text-align: left;
              margin-top: 10px;
              &:hover {
                background-color: #1abc9c;
              }

            }
          }

          &.expanded .submenu {
            display: block;
          }

          &.expanded ~ li {
            margin-top: -8px;
          }
        }
      }
    }

    .back-button {
      color: #ecf0f1;
      margin-top: 10px;
      padding: 10px;
      background-color: transparent;
      border-radius: 5px;
      margin-left: 5px;
      font-size: 18px;
      transition: background-color 0.3s ease;
      &:hover {
        background-color: #9a8c98;
      }
    }
  }

  .content {
    flex-grow: 1;
    padding: 20px;
    background-color: #ecf0f1;
    margin-top: 10px;


    .actions {
      display: flex;
      justify-content: flex-start;
      gap: 10px;
      margin-bottom: 20px;

      .el-input__inner {
        border-radius: 5px !important;
        box-shadow: none !important;
        border: 1px solid #ccc !important;
      }
    }

    .patient-list {
      margin-top: 20px;

      .el-table {
        margin-top: 20px;

        .el-button {
          font-size: 12px;
          padding: 5px 4px;
        }
      }
    }
  }
}
</style>
