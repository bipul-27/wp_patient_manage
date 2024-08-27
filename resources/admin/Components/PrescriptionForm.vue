<template>
    <div class="prescription-form-container">
      <el-card class="prescription-card" shadow="hover">
        <h2 class="card-title">Create Prescription</h2>
        <div class="field-container">
          <h3>Diagnosed With</h3>
          <el-input
            v-for="(disease, index) in diagnosisList"
            :key="index"
            v-model="disease.name"
            class="disease-entry"
            placeholder="Enter disease"
          />
          <div v-if="diagnosisList.length">
            <ul>
              <li v-for="(disease, index) in diagnosisList" :key="index">
                {{ index + 1 }}. {{ disease.name }}
                <el-button type="text" @click="removeDisease(index)">❌</el-button>
              </li>
            </ul>
          </div>
          <el-button type="primary" icon="el-icon-plus" @click="addDisease">Add Disease</el-button>
        </div>
  
    
        <div class="field-container">
          <h3>Medicines</h3>
          <div v-for="(medicine, index) in medicinesList" :key="index" class="medicine-entry">
            <el-select
              v-model="medicine.name"
              placeholder="Select Medicine"
              @change="fetchMedicineDetails(medicine)"
              filterable
            >
              <el-option
                v-for="option in medicineOptions"
                :key="option.id"
                :label="option.name"
                :value="option.name"
              />
            </el-select>
            <el-input v-model="medicine.type" placeholder="Medicine Type" :disabled="true" />
            <el-input v-model="medicine.dosage" placeholder="Dosage" />
            
            <div class="when-to-take">
  <el-checkbox 
    v-model="medicine.times.morning" 
    :checked="medicine.times.morning === 1"
    @change="toggleMorning"
  >
    Morning
  </el-checkbox>

  <el-checkbox 
    v-model="medicine.times.noon" 
    :checked="medicine.times.noon === 1"
    @change="toggleNoon"
  >
    Noon
  </el-checkbox>

  <el-checkbox 
    v-model="medicine.times.night" 
    :checked="medicine.times.night === 1"
    @change="toggleNight"
  >
    Night
  </el-checkbox>
</div>

  
            <el-input v-model="medicine.duration" placeholder="Duration" />
          </div>
          <div v-if="medicinesList.length">
            <ul>
              <li v-for="(medicine, index) in medicinesList" :key="index">
                {{ index + 1 }}. {{ medicine.name }} - {{ medicine.type }} - {{ medicine.dosage }} - {{ medicine.duration }} Days - 
                {{ medicine.times.morning ? 'Morning ' : '' }} 
                {{ medicine.times.noon ? 'Noon ' : '' }} 
                {{ medicine.times.night ? 'Night ' : '' }}
                <el-button type="text" @click="removeMedicine(index)">❌</el-button>
              </li>
            </ul>
          </div>
          <el-button type="primary" icon="el-icon-plus" @click="addMedicine">Add Medicine</el-button>
        </div>
  
        <div class="field-container">
          <h3>Tests</h3>
          <div v-for="(test, index) in testsList" :key="index" class="test-entry">
            <el-input v-model="test.name" placeholder="Test Name" />
            <el-input v-model="test.info" 
            autosize
            type="textarea" 
            placeholder="Extra Information" />
          </div>
          <div v-if="testsList.length">
            <ul>
              <li v-for="(test, index) in testsList" :key="index">
                {{ index + 1 }}. {{ test.name }} - {{ test.info }}
                <el-button type="text" @click="removeTest(index)">❌</el-button>
              </li>
            </ul>
          </div>
          <el-button type="primary" icon="el-icon-plus" @click="addTest">Add Test</el-button>
        </div>
  
        <div class="field-container">
          <h3>Advice</h3>
          <el-input type="textarea" v-model="advice" placeholder="Enter advice" />
        </div>
  
        <div class="form-actions">
          <el-button type="primary" @click="savePrescription">Create Prescription</el-button>
          <el-button @click="cancelPrescription">Cancel</el-button>
        </div>
      </el-card>
    </div>
  </template>
<script>
export default {
  name: 'PrescriptionForm',
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
      diagnosisList: [],
      medicinesList: [],
      testsList: [],
      advice: '',
      medicineOptions: [],
      saving: false 
    };
  },
  methods: {
    addDisease() {
      this.diagnosisList.push({ name: '' });
    },
    removeDisease(index) {
      this.diagnosisList.splice(index, 1);
    },

    addMedicine() {
      this.medicinesList.push({
        name: '',
        type: '',
        dosage: '',
        times: { morning: 0, noon: 0, night: 0 },
        duration: '',
      });
    },
    fetchMedicineDetails(medicine) {
      const selectedMedicine = this.medicineOptions.find((option) => option.name === medicine.name);
      if (selectedMedicine) {
        medicine.type = selectedMedicine.dosage_form;
      }
    },
    removeMedicine(index) {
      this.medicinesList.splice(index, 1);
    },

    addTest() {
      this.testsList.push({ name: '', info: '' });
    },
    removeTest(index) {
      this.testsList.splice(index, 1);
    },

    savePrescription(){
      this.saving = true;
      this.$post(`doctors/${this.doctorId}/patients/${this.patientId}/prescription`,{
        diagnosisList:this.diagnosisList,
        medicinesList:this.medicinesList,
        testsList:this.testsList,
        advice:this.advice,
      })
      .then(response => {
        this.$notify.success(response.message || 'Prescription created successfully');
        this.$router.go(0);
      })
      .catch(errors => {
          this.$handleError(errors);
          console.log(errors);
      })
      .finally(() => {
        this.saving = false;
      });
      
    },
    cancelPrescription() {
      this.$router.go(0);
    },
    fetchMedicineOptions() {
      this.saving = true;
      this.$get(`doctors/${this.doctorId}/medicine`, {
        search: this.searchQuery
      })
      .then(response => {
        console.log('Medicine options:', response.medicines);
        this.medicineOptions = response.medicines;
      })
      .catch(error => {
        this.$notify.error('Error fetching medicines.');
        console.error('API error:', error);
      })
      .finally(() => {
        this.saving = false;
      });
    },
    toggleMorning() {
      this.medicine.times.morning = this.medicine.times.morning === 1 ? 0 : 1;
    },
    toggleNoon() {
      this.medicine.times.noon = this.medicine.times.noon === 1 ? 0 : 1;
    },
    toggleNight() {
      this.medicine.times.night = this.medicine.times.night === 1 ? 0 : 1;
    }
  },
  mounted() {
    this.fetchMedicineOptions(); 
  }
};
</script>
<style scoped>
.prescription-form-container {
  padding: 20px;
}

.prescription-card {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.card-title {
  text-align: center;
  font-size: 24px;
  margin-bottom: 20px;
}

.field-container {
  margin-bottom: 30px;
}

.disease-entry,
.medicine-entry,
.test-entry {
  display: flex;
  gap: 10px;
  margin-bottom: 10px;
}

.medicine-entry .when-to-take {
  display: flex;
  align-items: center;
  gap: 10px;
}

.medicine-entry .when-to-take .el-checkbox {
  margin: 0;
  padding: 5px 10px;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.el-button--primary {
  --el-button-bg-color: #167c68;
}
.disease-entry{
    width: 250px;
  }


</style>
  
