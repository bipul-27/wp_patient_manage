<template>
    <div class="create-doctor-container">
        <el-button type="primary" @click="navigateBack">Back to Doctor List</el-button>
        <el-card class="create-doctor-card" shadow="hover">
            <h2>{{ isEdit ? 'Edit Doctor' : 'Create Doctor' }}</h2>
            <el-form :model="doctors" label-width="120px" class="create-doctor-form">
                <el-form-item label="Username">
                    <el-input v-model="doctors.username"></el-input>
                </el-form-item>
                <el-form-item label="Email">
                    <el-input v-model="doctors.email"></el-input>
                </el-form-item>
                <el-form-item label="Name">
                    <el-input v-model="doctors.name"></el-input>
                </el-form-item>
                <el-form-item label="Speciality">
                    <el-input v-model="doctors.speciality"></el-input>
                </el-form-item>
                <el-form-item label="Contact Info">
                    <el-input 
                    v-model="doctors.contact_info"
                    style="width: 429px"
                    :autosize="{ minRows: 2, maxRows: 4 }"
                    type="textarea">
                </el-input>
                </el-form-item>
                <el-form-item class="form-actions">
                    <el-button type="primary" @click="saveDoctor">{{ isEdit ? 'Update' : 'Create' }}</el-button>
                    <el-button @click="navigateBack">Cancel</el-button>
                </el-form-item>
            </el-form>
        </el-card>
    </div>
</template>

<script>
export default {
    name: 'CreateDoctor',
    data() {
        return {
            doctors: {
                username: '',
                email: '',
                name: '',
                speciality: '',
                contact_info: ''
            },
            isEdit: false,
            doctorId: null,
            saving: false
        };
    },
    methods: {
        navigateBack() {
            this.$router.push({ name: 'dashboard' });
        },
        fetchDoctors(id) {
            this.saving = true;
            this.$get(`doctors/${id}`)
            .then(response => {
                // console.log('API response:', response);
                this.doctors = response;

            })
            .catch(errors => {
                    this.$handleError(errors);
                    console.log(errors);
                })
             .finally(() => {
                 this.saving = false;
            });
            
        },
        saveDoctor(){
            this.saving = true;
            if(this.isEdit)
        {
            this.$put(`doctors/${this.doctorId}`,this.doctors)
            .then(response=>{
                this.$notify.success(response.message || 'Doctor Edited successfully');
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
        else{
            this.$post('doctors',{
                doctors: this.doctors,
            })
            .then(response => {
                this.$notify.success(response.message || 'Doctor Created successfully');
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
        },
        
    },
    mounted()
    {
        const doctorId = this.$route.params.id;
        if(doctorId)
    {
        this.isEdit = true;
        this.doctorId=doctorId;
        this.fetchDoctors(doctorId);
    }
    }
}
</script>

<style scoped>
.create-doctor-container {
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.create-doctor-card {
    width: 100%;
    max-width: 600px;
    margin-top: 20px;
}

.create-doctor-form {
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
</style>
