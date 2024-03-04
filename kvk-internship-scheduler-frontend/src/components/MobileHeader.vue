
<template>
  <div>
    <div class="header">
      <img class="logo" :src="layoutLogo" alt="KVK Logo">

    </div>
  </div>
</template>

<script>
import layoutLogo from '@/assets/Photos/KVKlogo.png';


export default {
    name: 'AppLayout',
  data() {
    return {
      layoutLogo,
    };
  },

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


</style>