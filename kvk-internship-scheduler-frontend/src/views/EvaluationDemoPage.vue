<template>
  <custom-header></custom-header>
  <div class="mainDiv">
    <div class="pageDescription">
      <h1 class="heading">Įsivertinimas</h1>
      <h2>Čia galite atsakyti į užduotus klausimus</h2>
    </div>
    <div class="CreationDiv">
      <v-list>
        <v-list-item v-for="template in templates" :key="template.id" @click="selectTemplate(template.id)">
          <v-list-item-content>
            <v-list-item-title>{{ template.name }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <div>
        <div class="TableDiv">
          <table v-if="selectedTemplate" style="border-bottom: #dddddd 1px solid;">
            <!-- Header row for removing answer options -->
            <tr style="border-right: #dddddd 1px solid;">
            </tr>

            <!-- Rows for answer options with remove icons -->
            <tr style="border-right: #dddddd 1px solid; height: 40px;">
              <td style="border: none; border-right: 1px #dddddd solid;"></td>
              <td v-for="(option, index) in answerOptions" :key="'header-' + index">
                {{ option.answer }}
              </td>
            </tr>

            <!-- Rows for questions with radio buttons and remove icons -->
            <tr v-for="(question, qIndex) in questionOptions" :key="qIndex">
              <td>
                {{ question.question }}
              </td>
              <td v-for="(option, oIndex) in answerOptions" :key="oIndex">
                <input type="radio" :name="'question' + question.id" :value="option.id"
                  v-model="selectedAnswers[question.id]" />
              </td>
            </tr>
          </table>
        </div>
        <div class="confirmButton">
          <v-btn @click="saveResults" v-if="selectedTemplate" width="200px" class="custom-gradient" rounded="xl"
            variant="elevated">Išsaugoti</v-btn>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";
import TS from "@/services/templates/templatesService"

export default {
  name: "EvaluationCreation",
  data() {
    return {
      internshipId: null,
      templateId: null,
      answerOptions: [],
      questionOptions: [],
      selectedAnswers: {},
      selectedQuestions: {},
      selectedTemplate: null,
      templates: [],
    };
  },
  components: {
    customHeader,
  },
  methods: {
    selectTemplate(templateId) {
      // TODO: kviesti tik kai skirtingas ID dabar nedariau nes pristatyms tuoj lol - as esu Tadas Andrijauskas
      TS.getTemplate(templateId).then(response => {
        this.selectedTemplate = response.data;
        this.templateId = response.data.id;
        this.questionOptions = response.data.questions;
        this.answerOptions = response.data.answers;
      });
    },
    getSelectedAnswersArray() {
    return Object.entries(this.selectedAnswers).map(([questionId, answerId]) => ({
      question_id: Number(questionId),
      answer_id: Number(answerId),
    }));
  },
  saveResults() {
    TS.createTemplateResultService(
      this.templateId,
      this.internshipId,
      this.getSelectedAnswersArray()
    ).then(response => console.log("išsaugoti!"))
  },
  },
  mounted() {
    this.internshipId = this.$route.params.internshipId;
    TS.getInternshipTemplates(this.internshipId).then(response => {
      this.templates = response.data;
    })
  }
};
</script>

<style scoped>
.confirmButton {
  display: flex;
  justify-content: center;
  margin: 30px 0;
}

.removeQuestion {
  display: flex;
}

table {
  width: 80%;
}

td,
th {
  border: 1px solid #dddddd;
  text-align: center;
  padding: 8px;
}

.heading {
  font-weight: bold;
}

.custom-gradient {
  background: linear-gradient(to top, #11257B, #048ACC);
  color: white;
}

.label {
  font-weight: 600;
}

.question {
  display: flex;
  flex-direction: row;
  justify-content: space-between;

}

.question input {
  margin: 5px 30px;
}

.answer {
  padding: 0 10px;
}

.tableHeader2Answers {
  display: flex;
  flex-direction: row;
}

.tableHeader {
  display: flex;
  border: 1px solid black;
  justify-content: space-between;
}

.tableHeader2 {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.mainTableDiv {
  display: flex;
  flex-direction: column;
  border: 1px solid black;
  height: 800px;
}

.tableHeader1 {
  align-items: center;
  justify-content: center;
  width: 100%;
  display: flex;
}

.mainDiv {
  padding: 0 100px;
}

h2 {
  display: inline-block;
  font-size: 15px;
  color: rgb(170, 167, 167);
  font-weight: 400;
}

.CreationDiv {
  padding: 0 250px;
}

.inputField {
  width: 50%;
}

.fullInput {
  display: flex;
  flex-direction: row;
  justify-content: space-evenly;
  align-items: center;
}

.remove-option {
  cursor: pointer;
  color: red;

}


.TableDiv {
  display: flex;
}

.rotated-text-div {

  writing-mode: vertical-rl;
  text-orientation: mixed;
  transform: rotate(180deg);
  align-self: flex-end;
  margin-bottom: -1px;
}
</style>
