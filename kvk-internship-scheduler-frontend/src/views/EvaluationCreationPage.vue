<template>
  <custom-header></custom-header>
  <div class="mainDiv">
    <div class="pageDescription">
      <h1>Klausimyno kūrimas</h1>
      <h2>Čia galite sukurti savo klausimyną</h2>
    </div>
    <div class="CreationDiv">
      <div>
        <div class="fullInput">
          <div class="inputField">
            <div class="text-subtitle-1 text-bold-emphasis">Klausimas</div>
            <v-text-field
            v-model="newQuestionText"
              density="compact"
              placeholder="Klausimas"
              variant="outlined"
            ></v-text-field>
          </div>
          <v-btn
          @click="addQuestion"
            width="200px"
            color="#0D47A1"
            rounded="xl"
            variant="elevated"
            type="submit"
            >Pridėti</v-btn
          >
        </div>

        <div class="fullInput">
          <div class="inputField">
            <div class="text-subtitle-1 text-bold-emphasis">
              Atsakymo variantas
            </div>
            <v-text-field
            v-model="newAnswerOption"
              density="compact"
              placeholder="Blogai, gerai, puikiai.."
              variant="outlined"
            ></v-text-field>
          </div>
          <v-btn
          @click="addAnswerOption"
            width="200px"
            color="#0D47A1"
            rounded="xl"
            variant="elevated"
            type="submit"
            >Pridėti</v-btn
          >
        </div>
      </div>

      <div class="TableDiv">
        <div class="rotated-text-div">
<div class="rotated-text">Panaikinti klausimą</div>



</div>

<table style="border-bottom: #dddddd 1px solid;">
    <tr style="border-right:#dddddd 1px solid;" >
        
        <td style="border: none;" ></td>
        <td :colspan="answerOptions.length" style="border-bottom:none">Panaikinti atsakymą</td>
    </tr>
    <tr style="border-right: #dddddd 1px solid; height:40px; ">

        <td style=" border:none; border-right: 1px #dddddd solid;"></td>
        <td style='border-top: none; border:none;' v-for="(option, index) in answerOptions" :key="'header-' + index">
            <span style='border:none;' @click="removeAnswerOption(index)" class="remove-option">X</span>
          </td>
    </tr>
<tr style="border-right:#dddddd 1px solid;">
<td  rowspan="2">Klausimai</td>
<td :colspan="answerOptions.length" style="border-bottom:none; border-right:none">Atsakymai</td>
</tr>
<tr style="border-right:#dddddd 1px solid;">
    <td style='border: none;' v-for="(option, index) in answerOptions" :key="'header-' + index">
        {{ option }}
      </td>
</tr>
<tr v-for="(question, index) in questions" :key="index">
    <td>{{ question.text }}</td>
    <td v-for="(option, oIndex) in answerOptions" :key="oIndex">
        <input type="radio" :name="'question' + qIndex" :value="option" />
      </td>
</tr>

</table>

      </div>
    </div>
  </div>
</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";

export default {
  name: "EvaluationCreation",
  data() {
    return {
      questions: [],
      newQuestionText: "",
      newAnswerOption: "",
      answerOptions: [],
    };
  },
  components: {
    customHeader,
  },

  methods: {
    removeAnswerOption(index) {
      this.answerOptions.splice(index, 1);
     
    },
    addAnswerOption() {
      if (this.newAnswerOption.trim() !== "") {
        this.answerOptions.push(this.newAnswerOption);
        this.newAnswerOption = ""; 
      }
    }, 

    addQuestion() {
      if (this.newQuestionText.trim() !== "") {
        this.questions.push({ text: this.newQuestionText });
        this.newQuestionText = "";
      }
    },

    removeQuestion(index) {
      this.questions.splice(index, 1);
    },
  },
};
</script>

<style scoped>
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
  padding: 0 200px;
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
  width: 45%;
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
