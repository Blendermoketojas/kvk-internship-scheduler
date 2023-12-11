<template>
  <custom-header></custom-header>
  <div class="mainDiv">
    <div class="pageDescription">
      <h1 class="heading">Įsivertinimas</h1>
      <h2>Čia galite atsakyti į užduotus klausimus</h2>
    </div>
    <div class="CreationDiv">
      <div>
        <div class="fullInput">
          <div class="inputField">
            <div class="label">Paieška pagal šablono ID:</div>
            <v-text-field
            v-model="templateId"
              density="compact"
              placeholder="ID"
              variant="outlined"
            ></v-text-field>
          </div>
          <v-btn
          @click="search"
            width="200px"
            class="custom-gradient"
            rounded="xl"
            variant="elevated"
            type="submit"
            >Ieškoti</v-btn
          >
        </div>

      <div class="TableDiv">


<table style="border-bottom: #dddddd 1px solid;">
    <!-- Header row for removing answer options -->
    <tr style="border-right: #dddddd 1px solid;">
    </tr>

    <!-- Rows for answer options with remove icons -->
    <tr style="border-right: #dddddd 1px solid; height: 40px;">
      <td style="border: none; border-right: 1px #dddddd solid;"></td>
      <td v-for="(option, index) in answerOptions" :key="'header-' + index">
        {{ option }}
      </td>
    </tr>

    <!-- Rows for questions with radio buttons and remove icons -->
    <tr v-for="(question, qIndex) in questionOptions" :key="qIndex">
      <td>
        {{ question.text }}
      </td>
      <td v-for="(option, oIndex) in answerOptions" :key="oIndex">
        <input type="radio" :name="'question' + qIndex" :value="option" />
      </td>
    </tr>
  </table>

      </div>

    </div>
    <div class="confirmButton">
    <v-btn
@click="saveAnswers"
    width="200px"
    class="custom-gradient"
    rounded="xl"
    variant="elevated"
    type="submit"
    >Išsaugoti</v-btn
  >
</div>
  </div>
</div>
</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";
import apiClient from "@/utils/api-client";

export default {
  name: "EvaluationCreation",
  data() {
    return {
      internshipId: null,
      answerOptions: [],
      questionOptions: [],
      templateId: "",
      isFocused: false,
      selectedAnswers: [],
    };
  },
  components: {
    customHeader,
  },

  methods: {
    getSelectedAnswerIdForQuestion(questionIndex) {
    return this.selectedAnswers[questionIndex];
  },

  saveAnswers() {
    const answers = this.questionOptions.map((question, index) => {
      const selectedAnswerId = this.getSelectedAnswerIdForQuestion(index);
      return {
        question_id: question.id,
        answer_id: selectedAnswerId
      };
    });

    const dataToSend = {
      template_id: this.templateId, 
      internship_id: this.internshipId,
      answers: answers
    };

    apiClient.post("/result/answer/create", dataToSend)
      .then(response => {
        console.log('Answers saved successfully', response);

      })
      .catch(error => {
        console.error('Error saving answers', error);
   
      });
  },

  getSelectedAnswerIdForQuestion(questionIndex) {

  },


    search() {
      if (this.templateId.trim() !== "") {
        var idToSend = Number(this.templateId);
        if (typeof idToSend === "number") {
          this.answerOptions = [];
          this.questionOptions = [];
          apiClient.post("/result/template/get", {id: idToSend})
        .then(response => {
          console.log(response);
          response.data.questions.sort(function(a, b) {
            return a.sequence - b.sequence;
          })
          for(let i=0; i< response.data.questions.length; i++) {
            this.questionOptions.push({text: response.data.questions[i].question});
          }
          response.data.answers.sort(function(a, b) {
            return a.sequence - b.sequence;
          })
          for(let i=0; i< response.data.answers.length; i++) {
            this.answerOptions.push(response.data.answers[i].answer);
          }
        }

        )
      }
        }
    },
  },

mounted(){
  this.internshipId = this.$route.params.internshipId;
}
};
</script>

<style scoped>
.confirmButton{
  display: flex;
  justify-content: center;
  margin: 30px 0;
}

.removeQuestion{
    display: flex;
}

table{
    width: 80%;
}
td, th {
    border: 1px solid #dddddd;
    text-align: center;
    padding: 8px;
  }

.heading{
    font-weight: bold;
}

.custom-gradient{
  background: linear-gradient(to top, #11257B, #048ACC);
  color: white;
}

.label{
    font-weight: 600;
}
.question{
    display: flex;
    flex-direction: row;
    justify-content: space-between;

}
.question input{
    margin: 5px 30px;
}
.answer{
    padding: 0 10px;
}

.tableHeader2Answers{
    display: flex;
    flex-direction: row;
}
.tableHeader {
  display: flex;
  border: 1px solid black;
justify-content: space-between;
}

.tableHeader2{
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
.tableHeader1{
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


  .TableDiv{
    display: flex;
  }
  .rotated-text-div{

    writing-mode: vertical-rl;
    text-orientation: mixed;
    transform: rotate(180deg);
    align-self: flex-end;
    margin-bottom: -1px;
  }
</style>
