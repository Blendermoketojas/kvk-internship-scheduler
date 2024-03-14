<template>

        <custom-header v-if="isDesktop" />
        <mobile-nav v-if="!isDesktop" />
        <div class="bodyDiv">
<div class="mainDiv">

  <div class="pageDescription">
    <h1>Pokalbiai</h1>
    <h2>Čia galite susisiekti su kitu naudotoju</h2>
  </div>
<div class="contentDiv">
    <div class="contact">
      <v-expansion-panels
      variant="accordion">
        <v-expansion-panel
      style="background-color: #f8f9fa "
          title="Asmeninės žinutės"
        >
      <v-expansion-panel-text>
      <v-btn class="chat-selection">Test role</v-btn>
      <v-btn class="chat-selection">Test role</v-btn>
      <v-btn class="chat-selection">Test role</v-btn>
      <v-btn class="chat-selection">Test role</v-btn>
    </v-expansion-panel-text>
        </v-expansion-panel>

        <v-expansion-panel
        style="background-color: #f8f9fa "
        title="Grupės"
        text="Grupiu pavadinimai"
      >
      </v-expansion-panel>
      
      </v-expansion-panels>

    </div>
    <div class="chat-room">
<div class="chat-name">

</div>
<div class="messages">

</div>

    </div>
</div>


</div>
</div>

</template>


<script>
import mobileNav from "@/components/MobileSidebar.vue";
import customHeader from "@/components/DesktopHeader.vue";

export default {
  components: { customHeader, mobileNav },

  data() {
    return {
      isDesktop: window.innerWidth > 950,

    };
  },

  methods: {
    handleResize() {
      this.isDesktop = window.innerWidth > 950;
    },
},
created() {
    window.addEventListener("resize", this.handleResize);
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleResize);
  },
}


</script>

<style scoped>
 .bodyDiv{
  width: 100%;
}
h2 {
  display: inline-block;
  font-size: 15px;
  color: rgb(170, 167, 167);
  font-weight: 400;
}
.mainDiv {
  padding: 0 200px;
  max-height: 100%;
}

.contentDiv{
  display: flex;
  justify-content: space-around;
  border-radius: 20px;
  background-color: #f8f9fa 
}
.contact{
  padding: 2px;
  padding-right: 0;
max-width: 300px;
}
.chat-room{
  width: 100%;
  min-height: 400px;
border-left: 1px solid #dddfdf;
 
}

.chat-name{
  height: 100px;
  width: 100%;
}

.chat-selection{
  width: 100%;
}
.v-expansion-panel-text__wrapper {
  padding: 0;
}
</style>