<template>
  <div class="main">
    <div class="kvkLogoDiv">
      <img class="logo" :src="layoutLogo" alt="KVK Logo" />
    </div>
    <div class="btn">
      <router-link to="/calendar">Mano kalendorius</router-link>
    </div>
    <div class="btn">
      <router-link to="/documents">Dokumentai</router-link>
    </div>
    <div class="btn">
      <router-link to="/my-results">Mano rezultatai</router-link>
    </div>
    <div class="btn">
      <router-link to="/learning-materials">Mokymosi medžiaga</router-link>
    </div>
    <div class="btn">
      <router-link to="/chat">Pokalbiai</router-link>
    </div>
    <div class="btn">
      <router-link to="/help">Pagalba</router-link>
    </div>
    <div class="btn">
      <router-link to="/profile-info" v-if="user.image_path">
        <img class="userImg" :src="fullImagePath" alt="User Image">
      </router-link>
      <router-link to="/profile-info" v-else>
        <img src="https://freesvg.org/img/abstract-user-flat-4.png" alt="Default Image">
      </router-link>
      <router-link to="/profile-info">{{ user.fullname }}</router-link>

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
