<template>
  <custom-header v-if="isDesktop" />
  <mobile-nav v-if="!isDesktop" />
  <div class="mainPage">
    <h1>Statistika</h1>
    <h2>Čia galite matyti statistiką</h2>

    <div class="contentDiv">
      <div class="searchDivs">
        <div class="groupSearchDiv">
          <group-search
            @update:selectedGroupId="onGroupSelected"
          ></group-search>
        </div>
        <div class="fieldDivDate">
          <div class="d-inline-block dateInput">
            <div class="text-subtitle-1 text-bold-emphasis">Nuo:</div>
            <input type="date" v-model="dateFrom" />
          </div>
          <div class="d-inline-block dateInput">
            <div class="text-subtitle-1 text-bold-emphasis">Iki:</div>
            <input type="date" v-model="dateTo" />
          </div>
        </div>
      </div>

      <div class="fieldDiv">
        <div class="text-subtitle-1 text-bold-emphasis">Vertinimo skalė</div>
        <v-autocomplete
          :items="templates"
          item-value="template.id"
          item-title="template.name"
          v-model="selectedTemplateId"
          :search-input.sync="searchTemplate"
          @update:search-input="fetchTemplates"
          @update:modelValue="onTemplateSelected"
          label="Pasirinkite skalės pavadinimą"
        ></v-autocomplete>
      </div>
    </div>

    <div class="question">
      <div class="leftArrow" @click="prevQuestion">
        <v-icon icon="mdi-chevron-left"></v-icon>
      </div>

      <div class="questionText">
        <p class="text-h6">{{ currentQuestion.name }}</p>
      </div>

      <div class="rightArrow" @click="nextQuestion">
        <v-icon icon="mdi-chevron-right"></v-icon>
      </div>
    </div>
    <div class="graphs">
      <div id="chart">
        <apexchart
          type="donut"
          :key="chartKey"
          :width="chartWidth.toString()"
          :options="options"
          :series="chartSeries"
        ></apexchart>
      </div>
    </div>
  </div>
</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";
import apiClient from "@/utils/api-client";
import groupSearch from "@/components/GroupSearch.vue";
import mobileNav from "@/components/MobileSidebar.vue";

export default {
  name: "Statistics",
  data() {
    return {
      chartWidth: 500,
      searchTemplate: "",
      isDesktop: window.innerWidth > 950,
      chartKey: 0,
      selectedGroupId: null,
      dateFrom: null,
      dateTo: null,
      chartData: null,
      questions: [],
      currentQuestionIndex: 0,
      templates: [],
      selectedTemplateId: null,
      options: {
        chart: {
          type: "donut",
          width: 500,
        },
        plotOptions: {
          pie: {
            offsetX: 0,
            offsetY: 25,
            customScale: 1,
            donut: {
              size: "0%",
            },
          },
        },
        dataLabels: {
          enabled: true,
          style: {
            fontSize: "30px",
          },
        },
      },
    };
  },
  components: {
    customHeader,
    groupSearch,
    mobileNav,
  },
  computed: {
    currentQuestion() {
      return this.questions[this.currentQuestionIndex] || {};
    },
    chartSeries() {
      return this.currentQuestion.answers
        ? this.currentQuestion.answers.map((a) => a.filledAmount)
        : [];
    },
  },

  methods: {
    updateChartWidth() {
    this.chartWidth = window.innerWidth < 500 ? 300 : 500;
  },

    handleResize() {
      this.isDesktop = window.innerWidth > 950;
    },

    nextQuestion() {
      if (this.currentQuestionIndex < this.questions.length - 1) {
        this.currentQuestionIndex++;
      }
    },

    prevQuestion() {
      if (this.currentQuestionIndex > 0) {
        this.currentQuestionIndex--;
      }
    },

    updateChartData(responseData) {
      this.questions = responseData;
      this.currentQuestionIndex = 0;
      const currentQuestion = this.questions[this.currentQuestionIndex];
      this.series = currentQuestion.answers.map((a) => a.filledAmount);

      this.options.labels = currentQuestion.answers.map((a) => a.answer);
      this.chartKey++;
    },

    onTemplateSelected() {
      if (
        this.selectedGroupId &&
        this.dateFrom &&
        this.dateTo &&
        this.selectedTemplateId
      ) {
        const payload = {
          student_group: this.selectedGroupId,
          date_from: this.dateFrom,
          date_to: this.dateTo,
          template_id: this.selectedTemplateId,
        };

        apiClient
          .post("/charts/get", payload)
          .then((response) => {
            console.log("atsakymai:", response.data);
            this.updateChartData(response.data);
          })
          .catch((error) => {
            console.error("Error fetching chart data:", error);
          });
      }
    },

    onGroupSelected(group) {
      this.selectedGroupId = group;
      this.fetchChartData();
    },
    fetchChartData() {
      if (this.selectedGroupId && this.dateFrom && this.dateTo) {
        const payload = {
          student_group: this.selectedGroupId,
          date_from: this.dateFrom,
          date_to: this.dateTo,
        };
        apiClient
          .post("/charts/templates", payload)
          .then((response) => {
            this.templates = response.data;
          })
          .catch((error) => {
            console.error("Error fetching chart data:", error);
          });
      }
    },
  },
  watch: {
    dateFrom(newDate, oldDate) {
      if (newDate !== oldDate) {
        this.fetchChartData();
      }
    },
    dateTo(newDate, oldDate) {
      if (newDate !== oldDate) {
        this.fetchChartData();
      }
    },
    selectedTemplateId(newVal, oldVal) {
      if (newVal !== oldVal) {
        this.fetchChartData();
      }
    },
  },
  created() {
    window.addEventListener("resize", this.handleResize);
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleResize);
  window.removeEventListener('resize', this.updateChartWidth);

  },

  mounted() {
  this.updateChartWidth(); 
  window.addEventListener('resize', this.updateChartWidth);
},
};
</script>

<style scoped>
.vue-apexcharts{
  display: flex;
  flex-direction: column;
  align-items: center;
}

.question {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.groupSearchDiv {
  width: 450px;
  height: 100px;
}
.searchDivs {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
}
.mainPage {
  padding: 0 200px;
}

h2 {
  display: inline-block;
  font-size: 15px;
  color: rgb(170, 167, 167);
  font-weight: 400;
}

.contentDiv {
  padding: 0 200px;
}

.multiple-divs {
  min-width: 220px;
}

.fieldDivDate {
  width: 450px;
  display: flex;
  justify-content: space-between;
  margin-bottom: 22px;
}

.dateInput {
  width: 49%;
}

input[type="date"]::-webkit-clear-button {
  display: none;
}

input[type="date"]::-webkit-inner-spin-button {
  display: none;
}

input[type="date"]::-webkit-calendar-picker-indicator {
  color: #2c3e50;
}

input[type="date"] {
  appearance: none;
  -webkit-appearance: none;
  color: #95a5a6;
  font-family: "Helvetica", arial, sans-serif;
  font-size: 20px;
  border: 1px solid #ecf0f1;
  background: #ecf0f1;
  padding: 5px;
  display: inline-block !important;
  visibility: visible !important;
  width: 100%;
  height: 60px;
  border-radius: 3px;
}

.questionText{
  text-align: center;
}

input[type="date"],
focus {
  color: #95a5a6;
  box-shadow: none;
  -webkit-box-shadow: none;
  -moz-box-shadow: none;
}

@media (max-width:1730px){
.contentDiv{
  padding: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.searchDivs{
  width: 100%;
}
.fieldDiv{
  width: 100%;
}
}

@media (max-width:1310px){
.mainPage{
  padding: 0 30px;
}

}

@media (max-width:970px){
.groupSearchDiv{
  width: 300px;
}

.fieldDivDate{
  width: 300px;

}
}
@media (max-width:670px){
.searchDivs{
flex-direction: column;

}

}
</style>
