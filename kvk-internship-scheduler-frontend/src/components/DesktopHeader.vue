<template>
    <div class="main">
      <div class="kvkLogoDiv">
        <img class="logo" :src="layoutLogo" alt="KVK Logo">
    </div>
      <div class="btn">
        <a>Mano kalendorius</a>
      </div>
      <div class="btn">
        <a>Dokumentai</a>
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

 

    </div>
  </template>

  <script>
import layoutLogo from '@/assets/Photos/KVKlogo.png';
import userIcon from '@/assets/Photos/UserIcon.png';

export default {
    name: 'AppLayout',
  data() {
    return {
      layoutLogo,
      userInfo: null, 
      userName: null,
      userIcon,
    };
  },
  mounted() {
    const userInfoString = localStorage.getItem('userInfo');
    
    if (userInfoString) {
      this.userInfo = JSON.parse(userInfoString);
      this.userName=this.userInfo.user.name;
    }
  }
}
</script>

<style scoped>
.main{

        display: flex; 
        align-items: center; 
        height: 100px;
     
}
.kvkLogoDiv{
    height: 100%;
    display: flex;
    justify-content: center; 
    align-items: center; 
    margin: 0 15px;
}
img{
    height: 60%;
}
a{
    font-weight: 600;

}



</style>