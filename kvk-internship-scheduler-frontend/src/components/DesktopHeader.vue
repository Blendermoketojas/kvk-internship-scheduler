<template>
  <div class="main">
    <div class="kvkLogoDiv">
      <img class="logo" :src="layoutLogo" alt="KVK Logo" />
    </div>
    <div class="btn">
      <a @click="goToCalendar">Mano kalendorius</a>
    </div>
    <div class="btn">
      <a @click="goToDocuments">Dokumentai</a>
    </div>
    <div class="btn">
      <a>Mano rezultatai</a>
    </div>
    <div class="btn">
      <a>Mokymosi medžiaga</a>
    </div>
    <div class="btn">
      <a>Pokalbiai</a>
    </div>
    <div class="btn">
      <a>Pagalba</a>
    </div>
    <div class="btn">
      <a @click="goToProfile" v-if="user.image_path">
        <img class="userImg" :src="fullImagePath" alt="User Image">
      </a>
      <a @click="goToProfile" v-else>
        <img src="https://freesvg.org/img/abstract-user-flat-4.png" alt="Default Image">
      </a>
      <a @click="goToProfile">{{ user.fullname }}</a>

    </div>
  </div>
</template>

<script>
import layoutLogo from "@/assets/Photos/KVKlogo.png";

export default {
  name: "AppLayout",
  data() {
    return {
      layoutLogo,
      user: {
        fullname: null,
        image_path: null,
      },
      baseImageUrl: 'http://localhost:8000',
    };
  },
  computed: {
    fullImagePath() {
      return this.user.image_path
        ? this.baseImageUrl+this.user.image_path
        : null;
    },
  },
  created() {
    const userStored = localStorage.getItem("user");
    if (userStored) {
      this.user = JSON.parse(userStored);
    }
  },
  mounted() {},
  methods: {
    goToCalendar() {
      this.$router.push("/calendar");
    },
    goToDocuments() {
      this.$router.push("/documents");
    },
    goToProfile() {
      this.$router.push("/profile-info");
    },
  },
};
</script>

<style scoped>
.main {
  display: flex;
  align-items: center;
  height: 100px;
}
.kvkLogoDiv {
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0 15px;
}
img {
  height: 60%;
}
a {
  font-weight: 600;
}

.userImg{
height: 40px;
  width: 40px;
  border-radius:50% ;
}

.btn:last-of-type{
  margin-left: 40%;

}
.btn:last-of-type img{
  margin: 0 10px;
}
</style>
