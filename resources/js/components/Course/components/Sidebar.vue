<template>
  <div class="sidebar">
    <Tabs>
      <Tab title="Содержимое" icon="template">
        <Templates></Templates>
      </Tab>
      <Tab title="Загрузки" icon="upload">
        <Uploads></Uploads>
      </Tab>
      <Tab title="Настройки" icon="settings">
        <LessonSettings></LessonSettings>
      </Tab>
    </Tabs>
  </div>
</template>

<script>

import Tab from '../components/tabs/Tab';
import Tabs from '../components/tabs/Tabs';

import Templates from "./templates/Templates";
import Uploads from "./Uploads";
import LessonSettings from "./LessonSettings";

export default {
    name: "Sidebar.vue",
    components: {Tabs, Tab, Templates, Uploads, LessonSettings},
    data () {
        return {
            
        }
    }
}

</script>

<style scoped>

</style>