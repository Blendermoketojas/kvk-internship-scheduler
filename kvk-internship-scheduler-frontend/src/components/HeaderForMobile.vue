
<template>
  <div>
    <div class="header">
      <img class="logo" :src="layoutLogo" alt="KVK Logo">
      <div class="userInfo">
      </div>
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
.header {
    display: flex;
    justify-content: right;
    align-items: center;
    height: 7rem;
    background: linear-gradient(141deg, #58acfa 0%, #81bef7 51%, #a9d0f5 75%);
}
#layoutLogo {
    position: relative;
    height: 4rem;
    margin: 1% 1%;
    top: 0;
    width: 10%;
    left: 0;
    transform: translateX(0);
}
.logo {
    position: absolute;
    margin-right: 2%;
    height: 4rem;
    margin: 1% 1%;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 10%;
}

body {
    font-family: sans-serif;
    line-height: 3;
    text-align: center;
}

.mainText {
    margin: 1em 0;
    font-size: 2em;
    font-weight: 900;
}

.userInfo {
    float: right;
    display: flex;
    justify-content: flex-end;
    width: 60%;
    margin-right: 2%;
}

.userIcon {
    height: 2.5rem;
    margin: auto 1%;
    width: 4%;
    vertical-align: middle;
}
.userName {
    font-size: 1em;
    font-weight: 550;
    float: right;
    margin-right: 2%;
}
.LogoutIcon {
    width: 100%;
    height: 100%;
}
.logoutBtn {
    width: 45px;
    background-color: transparent;
    height: 40px;
    border: none rgba(0, 0, 0, 0);
    margin: 0.5em auto;
    font-size: 10px;
}

.layoutBtn {
    width: 10%;
background-color: #68b4fc;
    color: #fff;
    height: 100%;
    padding: 0.5em 1em;
    border: none rgba(0, 0, 0, 0);
    margin: 0 0;
    font-size: 15px;

}

button {
    width: 30%;
    background-color: #58acfa;
    color: #fff;
    height: 20%;
    padding: 0.5em 1em;
    border: none rgba(0, 0, 0, 0);
    margin: 0.5em auto;
    font-size: 25px;
}
button:hover {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    cursor: pointer;
}

.profileInput,
textarea {
    display: block;
    margin: 0 auto;
}

.profileInfo {
    background-color: rgba(242, 243, 247, 255);
    width: 40%;
    margin: 3% auto;
    text-align: center;
    padding: 20px;
    box-sizing: border-box;
    border-radius: 5%;
}
.confirmBtn {
    width: 40%;
    height: 10%;
    font-size: 20px;
}

.profileIcon {
    width: 10%;
    height: 8%;
    display: block;
    margin: 0 auto;
}


</style>