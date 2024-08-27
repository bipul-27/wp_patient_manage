<template>
    <div class="appointmentTable">
        
      <el-table
        v-if="appointments && appointments.length > 0"
        :data="appointments"
        style="width: 100%;"
        stripe
      >
        <el-table-column
          prop="date"
          label="Appointment Date"
          sortable
        >
          <template v-slot="scope">
            {{ formatDate(scope.row.date) }}
          </template>
        </el-table-column>
  
        <el-table-column
          prop="start_time"
          label="Start Time"
        >
          <template v-slot="scope">
            {{ formatTime(scope.row.start_time) }}
          </template>
        </el-table-column>
  
        <el-table-column
          prop="end_time"
          label="End Time"
        >
          <template v-slot="scope">
            {{ formatTime(scope.row.end_time) }}
          </template>
        </el-table-column>
      </el-table>
      <div v-else>
        No appointments available.
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'AppointmentTable',
    props: {
      appointments: {
        type: Array,
        required: true
      }
    },
    methods: {
      formatDate(date) {
        return new Date(date).toLocaleDateString('en-GB');
      },
      formatTime(time) {
        const [hours, minutes] = time.split(':');
        return `${hours}:${minutes}`;
      },
      navigateToCreateAppointment()
      {
        this.$router.push({ name: 'makeAppointment', params: { id: this.doctorId } });
      }
    }
  };
  </script>

<style scoped>
.appointmentTable{
    margin-top: 14px;
}
</style>
  
