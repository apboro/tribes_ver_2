<template>
  <div class="lesson-settings">
    <label for="lesson_title" class="lesson-settings__label">
      Название части
    </label>
    <input type="text" id="lesson_title" class="input" v-model="lesson.lesson_meta.title">

    <div class="lesson-settings__published">
      <label class="toggle-switch">
        <input type="checkbox" id="published" v-model="lesson.isPublish">
        <span class="toggle-switch__slider"></span>
      </label>

      <label for="published" class="lesson-settings__published-label">Опубликовать сейчас</label>
    </div>


    <button
        type="submit"
        class="button-flat button-flat--danger lesson-settings__remove-btn"
        @click="showModal=true"
    >
      Удалить часть
    </button>

    <div class="modal-container">
      <transition name="modal" v-if="showModal">
        <div class="modal" @click.self="showModal=false">
          <div class="modal__wrapper">
            <div class="modal__container">
              <div class="modal__header">
                <slot name="header">
                  <span>Удаление</span>
                  <button class="modal__close-btn" @click="showModal=false">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path
                          d="M18.7 17.3C19.1 17.7 19.1 18.3 18.7 18.7C18.5 18.9 18.3 19 18 19C17.7 19 17.5 18.9 17.3 18.7L12 13.4L6.7 18.7C6.5 18.9 6.3 19 6 19C5.7 19 5.5 18.9 5.3 18.7C4.9 18.3 4.9 17.7 5.3 17.3L10.6 12L5.3 6.7C4.9 6.3 4.9 5.7 5.3 5.3C5.7 4.9 6.3 4.9 6.7 5.3L12 10.6L17.3 5.3C17.7 4.9 18.3 4.9 18.7 5.3C19.1 5.7 19.1 6.3 18.7 6.7L13.4 12L18.7 17.3Z"
                          fill="#6E6B7B"/>
                      <rect x="0.5" y="0.5" width="23" height="23" stroke="black"/>
                      <rect x="0.5" y="0.5" width="23" height="23" stroke="black" stroke-opacity="0.2"/>
                    </svg>
                  </button>
                </slot>
              </div>

              <div class="modal__body">
                Вы уверены, что хотите удалить из медиатовара {{ lesson.lesson_meta.title }}?
              </div>

              <div class="modal__footer">
                <slot name="footer">
                  <button class="button button--danger modal__footer-item" @click="removeLesson(lesson.id)">Удалить
                  </button>
                  <button class="button-outline button-outline--success modal__footer-item" @click="showModal=false">
                    Отмена
                  </button>
                </slot>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  name: "LessonSettings",
  data() {
    return {
      lesson: {
        lesson_meta: {
          title: ''
        }
      },
      showModal: false
    }
  },

  mounted() {
    window.lsettings = this;
  },

  watch: {
    activeLesson(lesson_id, oldCount) {
      this.lesson = this.$store.getters.getLessonById(lesson_id)[0];
    }
  },

  computed: {
    activeLesson() {
      return this.$store.getters.activeLesson
    }
  },

  methods: {
    setLesson(lesson) {
      this.lesson = this.$attrs.lesson;
    },

    removeLesson(id) {
      this.$store.commit('removeLesson', id);
      this.showModal = false;
      this.$root.$emit('autoSave');
      this.$store.commit('setActiveLesson', this.$store.getters.course.lessons[0].id);
    },
  }
}
</script>

<style scoped>
</style>