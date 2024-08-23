<template>
  <div class="patient-details-container">
    <aside class="sidebar">
      <nav class="nav">
        <ul>
          <li @click="setActiveTab('profile')" :class="{ active: activeTab === 'profile' }">Profile</li>
          <li @click="setActiveTab('appointments')" :class="{ active: activeTab === 'appointments' }">Appointments</li>
          <li @click="setActiveTab('drugs')" :class="{ active: activeTab === 'drugs' }">Drugs</li>
          <li @click="setActiveTab('tests')" :class="{ active: activeTab === 'tests' }">Tests</li>
          <li @click="setActiveTab('prescription')" :class="{ active: activeTab === 'prescription' }">Prescription</li>
          <li @click="setActiveTab('history')" :class="{ active: activeTab === 'history' }">History</li>
        </ul>
      </nav>
    </aside>
    <main class="content">
      <div v-if="activeTab === 'profile'" class="profile">
        <div class="profile-card">
          <div class="profile-header">
            <img :src="patientProfileImage" alt="Patient Profile Image" class="profile-image"/>
            <div class="profile-info">
              <h2>{{ patient.name }}</h2>
              <div class="profile-details">
                <span class="detail-item"><el-icon><UserFilled /></el-icon> {{ patient.gender }}</span>
                <span class="detail-item"><el-icon><LocationInformation /></el-icon> {{ patient.address }}</span>
                <span class="detail-item"><el-icon><Avatar /></el-icon> {{ patient.occupation }}</span>
                <span class="detail-item"><el-icon><FirstAidKit /></el-icon>{{ patient.dob }} ({{ patient.age }} Years)</span>
              </div>
              <div class="profile-details-2">
                <span class="detail-item-2"><el-icon><Iphone /></el-icon>{{ patient.contact_info }}</span>
                <span class="detail-item-2"><el-icon><Message /></el-icon>{{ patient.email }}</span>
              </div>
              <div class="card-header">
                <el-tag :type="tagType" class="condition-tag">{{ patient.health_condition }}</el-tag>
            </div>
            </div>
            <el-button type="primary" @click="navigateToEditPatient"> Edit</el-button>
          </div>
          <div class="profile-stats">
            <el-card style="width:  115px; margin-left: 30px" shadow="always">
            <div class="stat-item">
              <strong>{{ patient.bmi }}</strong>
              <span>BMI</span>
            </div>
          </el-card>
          <el-card style="width: 115px" shadow="always">
            <div class="stat-item">
              <strong>{{ patient.weight }} kg</strong>
              <span>Weight</span>
            </div>
          </el-card>
          <el-card style="width: 115px" shadow="always">
            <div class="stat-item">
              <strong>{{ patient.height }} cm</strong>
              <span>Height</span>
            </div>
          </el-card>
          <el-card style="width: 115px" shadow="always">
            <div class="stat-item">
              <strong>{{ patient.blood_pressure }}</strong>
              <span>Blood Pressure</span>
            </div>
          </el-card>
          </div>
        </div>
      </div>
      <div v-else-if="activeTab === 'appointments'">
        <div>
        <el-button type="primary" @click="navigateToCreateAppointment">New Appointment</el-button>
    </div>
        <appointment-table :appointments="appointments"></appointment-table>
      </div>
      <div v-else-if="activeTab === 'drugs'">
        <drugs :doctorId="doctorId" :patientId="patientId"></drugs>
      </div>
      <div v-else-if="activeTab === 'tests'">
        <Tests :doctorId="doctorId" :patientId="patientId"></Tests>
      </div>
      <div v-else-if="activeTab === 'prescription'">
  <prescription-form :doctorId="doctorId" :patientId="patientId"></prescription-form>
</div>
      <div v-else-if="activeTab === 'history'">
        <prescription-history :doctorId="doctorId" :patientId="patientId"></prescription-history>
      </div>
    </main>
  </div>
</template>

<script>
import AppointmentTable from './AppointmentTable.vue'; 
import PrescriptionForm from './PrescriptionForm.vue';
import PrescriptionHistory from './PrescriptionHistory.vue';
import Tests from './Tests.vue';
import Drugs from './Drugs.vue';
import { LocationInformation } from '@element-plus/icons-vue';
import { UserFilled  } from '@element-plus/icons-vue';
import { Avatar  } from '@element-plus/icons-vue';
import { FirstAidKit  } from '@element-plus/icons-vue';
import { Message  } from '@element-plus/icons-vue';
import { Iphone  } from '@element-plus/icons-vue';
export default {
  name: 'PatientDetails',
  computed: {
        tagType() {
            switch (this.patient.health_condition) {
                case 'critical':
                    return 'danger';
                case 'stable':
                    return 'warning';
                case 'normal':
                    return 'success';
                default:
                    return 'info';
            }
        }
    },
  components: {
    AppointmentTable, 
    PrescriptionForm,
    PrescriptionHistory,
    Tests,
    Drugs,
    UserFilled ,
    LocationInformation,
    Avatar,
    FirstAidKit,
    Message,
    Iphone
  },
  data() {
    return {
      activeTab: 'profile',
      doctorId: this.$route.params.doctorId,
      patientId: this.$route.params.patientId,
      patient:[],
      patientProfileImage: window.fluentFrameworkAdmin.asset_url+'/images/photo.jpg',
      appointments: [],
      saving: false
    };
  },
  methods: {
    setActiveTab(tab) {
      this.activeTab = tab;
    },
    fetchAppointments() {
      this.saving = true;
      this.$get(`doctors/${this.doctorId}/patients/${this.patientId}/appointments`)
        .then(response => {
          console.log(this.patientId);
          this.appointments = response.appointments;
        })
        .catch(errors => {
                this.$handleError(errors);
                console.log(errors);
        })
        .finally(() => {
                this.saving = false;
        });
    },
    fetchPatient() {
      this.$get(`doctors/${this.doctorId}/patients/${this.patientId}`, { 
      })
      .then(response => {
        this.patient = response.patient;
      })
      .catch(error => {
        this.$handleError(error);
        console.log(error);
      });
    },

    navigateToCreateAppointment(){
      this.$router.push({ name: 'makeAppointment', params: { id: this.doctorId } });
    },
    handleCancelPrescription() {
      // Handle the cancellation of the prescription creation
    },
    navigateToEditPatient(){
      this.$router.push({ name: 'edit-patient', params: { doctorId: this.doctorId, patientId: this.patientId } });
    },
  },
  mounted() {
    if (this.activeTab === 'appointments') {
      this.fetchAppointments(); 
    }
    else if (this.activeTab === 'profile'){
      this.fetchPatient();
    }
  },
  watch: {
    activeTab(newTab) {
      if (newTab === 'appointments') {
        this.fetchAppointments(); // Fetch appointments whenever the appointments tab is selected
      }
      else if (this.activeTab === 'profile'){
      this.fetchPatient();
    }
    },
  },
};
</script>

<style lang="scss" scoped>
.patient-details-container {
  display: flex;
  height: 100vh;

  .sidebar {
    width: 250px;
    background-color: #0e3633;
    color: #fff;
    padding: 40px 12px;
    height: fit-content;
    border-radius: 25px;
    margin-top: 50px;
    margin-right: 20px;
    font-size: 18px;
    
    .nav {
      ul {
        list-style: none;
        padding: 0;

        li {
          padding: 10px;
          cursor: pointer;

          &.active {
            background-color: #34495e;
          }

          &:hover {
            background-color: #1abc9c;
          }
        }
      }
    }
  }

  .content {
    flex-grow: 1;
    padding: 20px;
    background-color: #ecf0f1;

    .profile {
      .profile-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-top: 30px;
        
        .profile-header {
          display: flex;
          align-items: center;
          justify-content: space-between;

          .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
          }

          .profile-info {
            flex-grow: 1;
            margin-top: 30px;
            h2 {
              font-size: 35px;
              margin: 0;
            }
            .profile-details {
              display: flex;
              gap: 15px;
              margin-top: 25px;
              .detail-item {
                display: flex;
                align-items: center;
                font-size: 17px;
              }
            }
            .profile-details-2 {
              display: flex;
              gap: 15px;
              margin-top: 15px;
              .detail-item-2 {
                display: flex;
                align-items: center;
                font-size: 17px;
              }
            }
            .card-header {
              margin-top: 15px;
}
          }

          .edit-button {
            background-color: #167c68;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 8px;
            cursor: pointer;
          }
        }

        .profile-stats {
          display: flex;
          justify-content: space-between;
          margin-top: 55px;

          .stat-item {
            text-align: center;
            strong {
              display: block;
              font-size: 20px;
              color: #333;
            }
            span {
              display: block;
              font-size: 12px;
              color: #888;
            }
          }
        }

        .health-info {
          display: flex;
          justify-content: space-between;

          .own-diagnosis, .health-barriers {
            width: 48%;
            h3 {
              font-size: 16px;
              margin-bottom: 10px;
              color: #333;
            }
            .tag {
              display: inline-block;
              background-color: #f0f0f0;
              color: #333;
              padding: 5px 10px;
              border-radius: 12px;
              margin-right: 5px;
              font-size: 12px;
            }
          }
        }
      }
    }
  }
  .el-button{
    margin: 17px 0px;
    padding: 19px 15px;
    background-color: #167c68;
    border: none;
}
}

</style>
