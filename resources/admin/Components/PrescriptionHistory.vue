<template>
    <div class="prescription-history-container">
      <h2>Prescription History for {{ patient.name }}</h2>
      <div v-for="prescription in prescriptions" :key="prescription.id" class="prescription-card">
        <div class="prescription-header">
          <h3>Prescription</h3>
          <p><strong>Patient Name:</strong> {{ patient.name }}</p>
          <p><strong>Age:</strong> {{ patient.age }}</p>
        </div>
  
        <div class="prescription-details">
          <h4>Diagnosed With:</h4>
          <ul>
            <li v-for="diagnosis in prescription.diagnoses" :key="diagnosis.id">
              {{ diagnosis.diagnosis }}
            </li>
          </ul>
  
          <h4>Medicines:</h4>
          <ul>
            <li v-for="medicine in prescription.medicines" :key="medicine.id">
              {{ medicine.medicine_name }} - {{ medicine.medicine_type }} - {{ medicine.dosage }} - {{ medicine.duration }} - 
              {{ medicine.morning ? 'Morning ' : '' }}
              {{ medicine.noon ? 'Noon ' : '' }}
              {{ medicine.night ? 'Night ' : '' }}
            </li>
          </ul>
  
          <h4>Tests:</h4>
          <ul>
            <li v-for="test in prescription.tests" :key="test.id">
              {{ test.test_name }} - {{ test.extra_info }}
            </li>
          </ul>
  
          <h4>Advice:</h4>
          <p>{{ prescription.advice }}</p>
        </div>
  
        <div class="prescription-actions">
          <el-button type="primary" @click="downloadPDF(prescription)">Download as PDF</el-button>
          <el-button @click="printPrescription(prescription)">Print</el-button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import jsPDF from 'jspdf';
  import 'jspdf-autotable';
  
  export default {
    name: 'PrescriptionHistory',
    props: {
      doctorId: {
        type: Number,
        required: true,
      },
      patientId: {
        type: Number,
        required: true,
      },
    },
    data() {
      return {
        patient: {},
        prescriptions: [],
      };
    },
    methods: {
      fetchPatientDetails() {
        
        this.$get(`doctors/${this.doctorId}/patients/${this.patientId}`)
          .then(response => {
            this.patient = response.patient;
          })
          .catch(error => {
            this.$handleError(error);
            console.log(error);
          });
      },
      fetchPrescriptions() {
       
        this.$get(`doctors/${this.doctorId}/patients/${this.patientId}/prescription`)
          .then(response => {
            this.prescriptions = response.prescriptions;
          })
          .catch(error => {
            console.error(error);
          });
      },
      downloadPDF(prescription) {
        const doc = new jsPDF();
        doc.text('Prescription', 10, 10);
        doc.text(`Patient Name: ${this.patient.name}`, 10, 20);
        doc.text(`Age: ${this.patient.age}`, 10, 30);
        doc.text('Diagnosed With:', 10, 40);
        prescription.diagnoses.forEach((diagnosis, index) => {
          doc.text(`${index + 1}. ${diagnosis.diagnosis}`, 10, 50 + (index * 10));
        });
  
        doc.text('Medicines:', 10, 70);
        prescription.medicines.forEach((medicine, index) => {
          doc.text(`${index + 1}. ${medicine.medicine_name} - ${medicine.medicine_type} - ${medicine.dosage} - ${medicine.duration} - 
            ${medicine.morning ? 'Morning ' : ''}${medicine.noon ? 'Noon ' : ''}${medicine.night ? 'Night ' : ''}`, 10, 80 + (index * 10));
        });
  
        doc.text('Tests:', 10, 110);
        prescription.tests.forEach((test, index) => {
          doc.text(`${index + 1}. ${test.test_name} - ${test.extra_info}`, 10, 120 + (index * 10));
        });
  
        doc.text('Advice:', 10, 150);
        doc.text(prescription.advice, 10, 160);
  
        doc.save(`Prescription-${this.patient.name}-${prescription.id}.pdf`);
      },
      printPrescription(prescription) {
        const printContent = this.$refs[`prescription-${prescription.id}`];
        const printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write(printContent.innerHTML);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
      },
    },
    mounted() {
      this.fetchPatientDetails();
      this.fetchPrescriptions();
    },
  };
  </script>
  
  <style scoped>
  .prescription-history-container {
    padding: 20px;
  }
  
  .prescription-card {
    margin-bottom: 30px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: white;
  }
  
  .prescription-header {
    margin-bottom: 20px;
  }
  
  .prescription-details h4 {
    margin-top: 10px;
  }
  
  .prescription-actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
  }
  
  .el-button--primary {
    --el-button-bg-color: #167c68;
  }
  </style>
  