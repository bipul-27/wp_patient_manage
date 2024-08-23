<template>
    <div class="make-appointment-container">
      <el-card class="appointment-card" shadow="hover">
        <h2 class="card-title">Make an Appointment</h2>
  
        <el-form :model="appointment" label-width="120px" class="appointment-form">
  
          <!-- Contact Number Filter -->
          <el-form-item label="Contact Number">
            <el-select
              v-model="appointment.patient_id"
              filterable
              placeholder="Select Contact Number"
              @change="fetchPatientDetails"
            >
              <el-option
                v-for="patient in patients"
                :key="patient.id"
                :label="patient.contact_info"
                :value="patient.id"
              />
            </el-select>
          </el-form-item>

          <!-- Patient Name Filter -->
          <el-form-item label="Patient Name">
            <el-select
              v-model="appointment.patient_id"
              filterable
              placeholder="Select Patient"
              @change="fetchPatientDetails"
            >
              <el-option
                v-for="patient in patients"
                :key="patient.id"
                :label="patient.name"
                :value="patient.id">
              </el-option>
            </el-select>
          </el-form-item>
  
          <!-- Date Picker -->
          <el-form-item label="Date">
            <el-date-picker
              v-model="appointment.date"
              type="date"
              placeholder="Select Date"
            ></el-date-picker>
          </el-form-item>
  
          <!-- Time Pickers -->
          <el-form-item label="Start Time">
            <el-time-picker
              v-model="appointment.start_time"
              placeholder="Select Start Time"
            ></el-time-picker>
          </el-form-item>
  
          <el-form-item label="End Time">
            <el-time-picker
              v-model="appointment.end_time"
              placeholder="Select End Time"
            ></el-time-picker>
          </el-form-item>
  
          <!-- Buttons -->
          <el-form-item class="form-actions">
            <el-button type="primary" @click="makeAppointment">Make Appointment</el-button>
            <el-button type="success" @click="navigateToCreatePatient">Create Patient</el-button>
          </el-form-item>
        </el-form>
      </el-card>
    </div>
  </template>
  
  <script>
  export default {
    name: 'MakeAppointment',
    data() {
      return {
        doctorId: this.$route.params.id,
        patients: [],
        appointment: {
          patient_id: '',
          doctor_id: '',
          date: '',
          start_time: '',
          end_time: ''
        },
        saving: false
      };
    },
    methods: {
      fetchPatients() {
        this.$get(`doctors/${this.doctorId}/patients`)
          .then(response => {
            this.patients = response.patients;
          })
          .catch(error => {
            this.$handleError(error);
        console.log(error);
          });
      },
      fetchPatientDetails() {
        // Fetch patient details if needed when a patient is selected this.appointment.patient_id
      },
      makeAppointment(){
        this.saving = true;
        const formattedAppointment = {
    ...this.appointment,
    date: this.appointment.date.toISOString().split('T')[0], 
    start_time: this.appointment.start_time.toTimeString().split(' ')[0], 
    end_time: this.appointment.end_time.toTimeString().split(' ')[0], 
  };

  console.log(formattedAppointment);
          this.$post(`doctors/${this.doctorId}/appointments`, {
            appointment: formattedAppointment,
          })
            .then(response => {
              this.$notify.success('Appointment made successfully!');
              // this.$router.push({ name: 'allAppointment', params: { id: this.doctorId } });
              this.$router.go(-1);
            })
            .catch(error => {
              this.$notify.error('There was an error making the appointment.');
              console.error(error);
            })
            .finally(() => {
                this.saving = false;
            });
        },
      navigateToCreatePatient() {
        this.$router.push({ name: 'create-patient', params: { id: this.doctorId } });
      }
    },
    mounted() {
      this.fetchPatients();
    }
};
  </script>
  
  <style lang="scss" scoped>
  .make-appointment-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
  
    .appointment-card {
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
  
    .appointment-form {
      width: 100%;
    }
  
    .form-actions {
      display: flex;
      justify-content: space-between;
      gap: 10px;
    }
  }
  </style>
  