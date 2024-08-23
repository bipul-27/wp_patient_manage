<template>
    <div class="tests-page">
        <el-card class="tests-card" shadow="hover">
            <h2>Patient Tests</h2>
            <el-table :data="tests" stripe>
                <el-table-column prop="test_name" label="Test Name"></el-table-column>
                <el-table-column prop="extra_info" label="Extra Info"></el-table-column>
            </el-table>
        </el-card>
    </div>
</template>

<script>
export default {
    name: 'Tests',
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
            tests: [],
        };
    },
    methods: {
        fetchTests() {
            const patientId = this.$route.params.patientId;
            this.$get(`doctors/${this.doctorId}/patients/${this.patientId}/tests`)
                .then(response => {
                    this.tests = response.tests;
                })
                .catch(errors => {
                    this.$handleError(errors);
                    console.log(errors);
                });
        },
    },
    mounted() {
        this.fetchTests();
    }
}
</script>

<style scoped>
.tests-page {
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.tests-card {
    width: 100%;
    max-width: 600px;
    margin-top: 20px;
}
</style>
