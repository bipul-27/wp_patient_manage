<template>
    <div class="drugs-page">
        <el-card class="drugs-card" shadow="hover">
            <h2>Patient Drugs</h2>
            <el-table :data="drugs" stripe>
                <el-table-column prop="medicine_name" label="Medicine"></el-table-column>
                <el-table-column prop="created_at" label="Given Date"></el-table-column>
            </el-table>
        </el-card>
    </div>
</template>

<script>
export default {
    name: 'Drugs',
    props: {
    doctorId: {
      type: Number, 
      required: true
    },
    patientId: {
      type: Number, 
      required: true
    },
    
  },
    data() {
        return {
            drugs: [],
        };
    },
    methods: {
        fetchDrugs() {
            const patientId = this.$route.params.patientId;
            this.$get(`doctors/${this.doctorId}/patients/${this.patientId}/drugs`)
                .then(response => {
                    this.drugs = response.drugs;
                })
                .catch(errors => {
                    this.$handleError(errors);
                    console.log(errors);
                });
        },
    },
    mounted() {
        this.fetchDrugs();
    }
}
</script>

<style scoped>
.drugs-page {
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
   
}

.drugs-card {
    width: 100%;
    max-width: 600px;
    margin-top: 20px;
}
</style>
