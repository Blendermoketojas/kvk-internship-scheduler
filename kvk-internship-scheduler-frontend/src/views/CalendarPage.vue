<template>
 <header-nav/>
    <DxScheduler   id="scheduler"  :adaptivity-enabled="true" :views="views" current-view="week" >

    </DxScheduler>

  </template>
  
  <script>
  import 'devextreme/dist/css/dx.light.css'; 
  import { DxScheduler} from 'devextreme-vue/scheduler';
  import HeaderNav from "@/components/DesktopHeader.vue";

  export default {
   
    components: {
        DxScheduler,
        HeaderNav
    },
    name: 'CalendarComponent',
    data() {
      return {
        views: ['month','week'],
      };
    },
    methods: {

    },
  };
  </script>
<style scoped>
#scheduler {
    height: 600px;
}
</style>
