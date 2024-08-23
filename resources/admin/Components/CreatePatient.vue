<template>
    <div class="create-patient-container">
        <el-button type="primary" @click="navigateBack">Back to Patient List</el-button>
        <el-card class="create-patient-card" shadow="hover">
            <h2>{{ isEdit ? 'Edit Patient' : 'Create Patient' }}</h2>
            <el-form :model="patients" label-width="120px" class="create-patient-form" enctype="multipart/form-data">
                <el-form-item label="Name">
                    <el-input v-model="patients.name"></el-input>
                </el-form-item>
                <el-form-item label="Email">
                    <el-input v-model="patients.email"></el-input>
                </el-form-item>
                <el-form-item label="Age">
                    <el-input type="number" v-model="patients.age"></el-input>
                </el-form-item>
                <el-form-item label="Gender">
                    <el-select v-model="patients.gender" placeholder="Select Gender">
                        <el-option label="Male" value="male"></el-option>
                        <el-option label="Female" value="female"></el-option>
                        <el-option label="Other" value="other"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Contact Number">
                    <el-input v-model="patients.contact_info"></el-input>
                </el-form-item>
                <el-form-item label="Health Condition">
                    <el-select v-model="patients.health_condition" placeholder="Select Health Condition">
                        <el-option label="Critical" value="critical"></el-option>
                        <el-option label="Stable" value="stable"></el-option>
                        <el-option label="Normal" value="normal"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Address">
                    <el-input v-model="patients.address"></el-input>
                </el-form-item>
                <el-form-item label="Occupation">
                    <el-input v-model="patients.occupation"></el-input>
                </el-form-item>
                <el-form-item label="Date of Birth">
                    <el-date-picker v-model="patients.dob" type="date" placeholder="Select Date of Birth"></el-date-picker>
                </el-form-item>
                <el-form-item label="Weight (kg)">
                    <el-input type="number" v-model="patients.weight"></el-input>
                </el-form-item>
                <el-form-item label="Height (cm)">
                    <el-input type="number" v-model="patients.height"></el-input>
                </el-form-item>
                <el-form-item label="Blood Group">
                    <el-input v-model="patients.blood_group"></el-input>
                </el-form-item>
                <el-form-item label="Blood Pressure">
                    <el-input v-model="patients.blood_pressure"></el-input>
                </el-form-item>
                <el-form-item label="BMI">
                    <el-input type="number" v-model="patients.bmi"></el-input>
                </el-form-item>
                <el-form-item class="form-actions">
                    <el-button type="primary" @click="savePatient">{{ isEdit ? 'Update' : 'Create' }}</el-button>
                    <el-button @click="navigateBack">Cancel</el-button>
                </el-form-item>
            </el-form>
        </el-card>
    </div>
</template>

<script>
export default {
    name: 'CreatePatient',
    data() {
        return {
            doctorId: this.$route.params.doctorId,
            doctorId1: this.$route.params.id,
            patientId: this.$route.params.patientId,
            patients: {
                name: '',
                email: '',
                age: '',
                gender: '',
                contact_info: '',
                health_condition: '',
                address: '',
                occupation: '',
                dob: '',
                weight: '',
                height: '',
                blood_group: '',
                blood_pressure: '',
                bmi: ''
            },
            isEdit: false,
            saving: false
        };
    },
    methods: {
        navigateBack() {
            // this.$router.push({ name: 'patient-management', params: { id: this.doctorId } });
            this.$router.go(-1); 
        },
        fetchPatient(id) {
            this.saving = true;
            this.$get(`doctors/${this.doctorId}/patients/${id}`)
            .then(response => {
                this.patients = response.patient;
            })
            .catch(errors => {
                this.$handleError(errors);
                console.log(errors);
            })
            .finally(() => {
                this.saving = false;
            });
        },
        savePatient() {
            this.saving = true;
            
            if (this.isEdit) {
               
                this.$put(`doctors/${this.doctorId}/patients/${this.patientId}`, this.patients)
                .then(response => {
                    this.$notify.success(response.message || 'Patient edited successfully');
                    this.navigateBack();
                })
                .catch(errors => {
                    this.$handleError(errors);
                    console.log(errors);
                })
                .finally(() => {
                    this.saving = false;
                });
            } else {
                const formattedPatient = {
                ...this.patients,
                dob: this.patients.dob.toISOString().split('T')[0]
            };
            console.log(formattedPatient);
                this.$post(`doctors/${this.doctorId1}/patients`, {
                patients:formattedPatient,
            })
                .then(response => {
                    this.$notify.success(response.message || 'Patient created successfully');
                    this.navigateBack();
                })
                .catch(errors => {
                    this.$handleError(errors);
                    console.log(errors);
                })
                .finally(() => {
                    this.saving = false;
                });
            }
        }
    },
    mounted() {
        if (this.patientId) {
            this.isEdit = true;
            this.fetchPatient(this.patientId);
        }
    }
}
</script>

<style scoped>
.create-patient-container {
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.create-patient-card {
    width: 100%;
    max-width: 600px;
    margin-top: 20px;
}

.create-patient-form {
    width: 100%;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
.el-button--primary {
    
    --el-button-bg-color: #167c68;
}
.el-select {
    width: 97.5%;
}
</style>
