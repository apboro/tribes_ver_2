<template>
  <div class="course-settings image" v-show="show">
    <div class="course-settings__overlay" @click="show = false"></div>
    <div class="course-settings__container">
      <div class="course-settings__head">
        <div class="course-settings__main-title-wrapper">
          <button class="button-light course-settings__back-btn" @click="show = false">
            <span>Назад</span>
          </button>

          <h2 class="course-settings__main-title">
            Настройки медиаконтента
          </h2>
        </div>

        <div class="course-settings__auxiliary">
          <div class="course-settings__alert-wrapper">
                        <span>
                            {{ timeValue }}
                        </span>

            <template v-if="isSavingTime">
                            <span class="course-settings__saving-spinner">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                     xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd"
                                                                              d="M12.8208 7.5C12.8208 8.55236 12.5088 9.58108 11.9241 10.4561C11.3394 11.3311 10.5084 12.0131 9.53619 12.4158C8.56394 12.8185 7.4941 12.9239 6.46196 12.7186C5.42983 12.5133 4.48175 12.0065 3.73762 11.2624C2.99349 10.5183 2.48673 9.57018 2.28143 8.53804C2.07612 7.5059 2.18149 6.43606 2.58421 5.46381C2.98693 4.49156 3.66891 3.66056 4.54392 3.0759C5.41892 2.49125 6.44765 2.17919 7.5 2.17919V0C6.82047 0 6.14793 0.0923104 5.5 0.271584C4.73357 0.483647 4.00158 0.817396 3.33323 1.26398C2.09986 2.08809 1.13856 3.25943 0.570907 4.62987C0.00324965 6.00032 -0.145275 7.50832 0.144114 8.96318C0.433503 10.418 1.14781 11.7544 2.1967 12.8033C3.2456 13.8522 4.58197 14.5665 6.03683 14.8559C7.49168 15.1453 8.99968 14.9968 10.3701 14.4291C11.7406 13.8614 12.9119 12.9001 13.736 11.6668C14.1826 10.9984 14.5164 10.2664 14.7284 9.5C14.9077 8.85208 15 8.17953 15 7.5H12.8208Z"
                                                                              fill="url(#paint0_linear_344_6349)"/><defs><linearGradient
                                    id="paint0_linear_344_6349" x1="6.875" y1="1.57605e-08" x2="15" y2="3.43872e-08"
                                    gradientUnits="userSpaceOnUse"><stop stop-color="#28C76F"/><stop offset="0.46938"
                                                                                                     stop-color="#28C76F"
                                                                                                     stop-opacity="0.378504"/><stop
                                    offset="1" stop-color="#28C76F" stop-opacity="0"/></linearGradient></defs></svg>
                            </span>
            </template>
          </div>

          <button type="submit" class="button button--success course-settings__auxiliary-btn" @click="save()">
            Сохранить
          </button>
        </div>
      </div>

      <div class="course-settings__content">
        <div class="course-settings__item">
          <h3 class="course-settings__title">
            Оплата и публикация
          </h3>

          <label for="course_title" class="course-settings__label course-settings__label--first-item">
            Название медиаконтента
          </label>
          <input type="text" id="course_title" class="input" v-model="course.course_meta.title">

          <div class="course-settings__group">
            <div class="course-settings__group-item">
              <input type="number" id="course_cost" class="input" v-model="course.course_meta.cost">
              <label for="course_cost" class="course-settings__label">
                Стоимость (руб.)
              </label>
            </div>
          </div>

          <div class="course-settings__group">
            <div class="course-settings__group-item">
              <input
                  type="number"
                  id="course_period"
                  class="input"
                  v-model="access_days"
                  :disabled="!!(this.course.course_meta.isEthernal || this.course.course_meta.deactivation_date)"
                  placeholder="0"
              >
              <label for="course_period" class="course-settings__label">
                Срок доступа (дней)
              </label>
            </div>

            <div class="course-settings__toggle-switch" >
              <label class="toggle-switch">
                <input type="checkbox" id="course_ethernal" v-model="course.course_meta.isEthernal" :disabled="!!this.course.course_meta.deactivation_date">
                <span class="toggle-switch__slider"></span>
              </label>

              <label for="course_ethernal" class="course-settings__toggle-switch-label">
                Бессрочный
              </label>
            </div>
          </div>

          <div class="course-settings__group">
            <div class="course-settings__group-item">
              <input
                  type="datetime-local"
                  :min="today()"
                  id="publication_date"
                  class="input"
                  :max="getMaxDate()"
                  :disabled="course.course_meta.isPublished"
                  v-model="course.course_meta.publication_date"
              />
              <label for="activation_date" class="course-settings__label">
                Дата публикации
              </label>
            </div>

            <div class="course-settings__toggle-switch">
              <label class="toggle-switch">
                <input type="checkbox" id="course_published" v-model="course.course_meta.isPublished">
                <span class="toggle-switch__slider"></span>
              </label>
              <label for="course_published" class="course-settings__toggle-switch-label">
                Опубликовать сейчас
              </label>
            </div>
          </div>

          <div class="course-settings__group">
            <div class="course-settings__group-item">
              <input
                  type="datetime-local"
                  :min="today()"
                  id="activation_date"
                  class="input"
                  :max="getMaxDate()"
                  :disabled="course.course_meta.isActive"
                  v-model="course.course_meta.activation_date"
              />
              <label for="publication_date" class="course-settings__label">
                Дата активации
              </label>
            </div>

            <div class="course-settings__toggle-switch">
              <label class="toggle-switch">
                <input type="checkbox" id="course_activated" v-model="course.course_meta.isActive">
                <span class="toggle-switch__slider"></span>
              </label>
              <label for="course_activated" class="course-settings__toggle-switch-label">
                Активировать сейчас
              </label>
            </div>
          </div>

          <div class="course-settings__group">
            <div class="course-settings__group-item">
              <input
                  type="datetime-local"
                  id="deactivation_date"
                  class="input"
                  :min="getMinDate()"
                  v-model="course.course_meta.deactivation_date"
              />
              <label for="deactivation_date" class="course-settings__label">
                Дата деактивации
              </label>
            </div>
          </div>

          <div class="course-settings__link-wrapper">
            <p class="course-settings__link-label">
              Ссылка на платежную страницу медиатовара:
            </p>

            <div class="course-settings__link-group">
              <a :href="getPaymentLink" target="_blank" class="course-settings__link-text">перейти</a>
              <span class="course-settings__link-divider"></span>
              <span class="course-settings__link-text" @click="copyText(getPaymentLink)">скопировать</span>
            </div>
          </div>

          <div class="course-settings__copied-msg" v-if="isCopied">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-check">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>Скопировано</span>
          </div>

          <div class="meta-settings">
            <!--                        <div class="course-settings__group">-->
<!--            <label class="course-settings__label course-settings__label&#45;&#45;first-item">-->
<!--              Текст благодарности за покупку-->
<!--            </label>-->
<!--            <textarea class="input" name="thanks_text" cols="30" rows="5"-->
<!--                      v-model="course.course_meta.thanks_text"></textarea>-->
            <!--                        </div>-->
            <div class="course-settings__group">
              <div class="course-settings__toggle-switch">
                <label class="toggle-switch">
                  <input type="checkbox" id="shipping_noty" v-model="course.course_meta.shipping_noty">
                  <span class="toggle-switch__slider"></span>
                </label>

                <label for="shipping_noty" class="course-settings__toggle-switch-label">
                  Получать уведомления о покупках на почту
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="course-settings__item">
          <h3 class="course-settings__title">
            Платёжная страница
          </h3>

          <label for="" class="course-settings__label course-settings__label--first-item">
            Изображение
          </label>

          <div class="course-settings__subitem-wrapper">
            <div class="course-settings__subitem">
              <div class="course-settings__image-container" @click.prevent="initUploader">
                <div class="course-settings__image-description">
                  <p class="course-settings__image-text">
                    Перетащите изображение или нажмите сюда и выберите на вашем устройстве
                  </p>
                  <p class="course-settings__image-text">
                    Поддерживаемые форматы JPEG, PNG, GIF
                  </p>
                  <p class="course-settings__image-text course-settings__image-text--bold">
                    Не более 2МБ
                  </p>
                </div>
              </div>

              <div class="course-settings__elem">
                <label for="payment_title" class="course-settings__label">
                  Заголовок
                </label>

                <input
                    type="text"
                    id="payment_title"
                    class="input"
                    placeholder="Текст заголовка"
                    v-model="course.course_meta.payment_title"
                >
              </div>

              <div class="course-settings__elem">
                <div
                    class="course-settings__editor media-content-text-formatting"
                    ref="editor"
                    @drop.prevent
                ></div>
              </div>
            </div>

            <div class="course-settings__subitem">
              <div class="course-settings__preview">
                <div class="course-settings__preview-img">
                  <img :src="getUrl" alt="картинка">
                </div>
              </div>

              <h2 class="course-settings__preview-title">
                {{ course.course_meta.payment_title }}
              </h2>

              <div
                  class="course-settings__preview-text media-content-text-formatting"
                  v-html="course.course_meta.payment_description"
              ></div>

              <button class="button-outline course-settings__buy-btn button-outline--success">
                Купить
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Quill from "quill";

export default {
  name: "CourseSettings",

  data() {
    return {
      show: false,
      preview: {
        id: 0,
      },
      isSavingTime: false,
      timeValue: '',
      quill: null,
      isCopied: false,
      activation_date: null,
      publication_date: null,
      deactivation_date: null,
    }
  },

  mounted() {
    window.courseSettings = this;

    this.$root.$on('autoSave', (data) => {
      this.isSavingTime = true;
      this.timeValue = '✌ Мы сохраняем вашу работу';
    });

    this.$root.$on('autoSaveIsDone', (data) => {
      this.isSavingTime = false;
      this.timeValue = `✌ Мы сохранили вашу работу автоматически в ${new Date().toLocaleTimeString().toString()}`;
    });
  },

  computed: {
    course() {
      return this.$store.getters.course;
    },

    access_days: {
      get: function () {
        return this.$store.getters.course.course_meta.access_days == 0 ? '' : this.$store.getters.course.course_meta.access_days;
      },
      set: function (newValue) {
        if (newValue == '') {
          newValue = 0;
        }
        this.$store.commit('setCourseAccessDays', newValue);
      }
    },

    getUrl() {
      return this.preview.url ? this.preview.url : '/images/no-image.svg'
    },

    getPaymentLink() {
      return this.course.course_meta.payment_link;
    },
  },

  watch: {
    "course.course_meta.preview":
        {
          handler: function (val, oldVal) {
            this.getFileFromServer(val);
          },
          deep: false
        },

    "course.course_meta.payment_description":
        {
          handler: function (val, oldVal) {
            if (!this.quill) {
              this.initQuill();
            }
          },
          deep: false
        }
  },

  methods: {
    today(){
      return new Date().toISOString().slice(0,16)
    },

    getMinDate(){
      let deactivation_date_from = (this.course.course_meta.activation_date > this.course.course_meta.publication_date) ?
      new Date(this.course.course_meta.activation_date) :
      new Date(this.course.course_meta.publication_date);

      deactivation_date_from.setDate(deactivation_date_from.getDate()+1);
      return deactivation_date_from.toISOString().slice(0,16);
    },

    getMaxDate(){
      let max_date = new Date(this.course.course_meta.deactivation_date);
      max_date.setDate(max_date.getDate()-1);
      return max_date.toISOString().slice(0,16);
    },

    getFileFromServer(id) {
      axios({url: '/api/file/get', data: {id: id}, method: 'POST'})
          .then(resp => {
            if (resp.data.status === 'ok') {
              this.preview = resp.data.file;
            }
          })
          .catch(err => {
            console.log(err);
          })
    },

    initUploader() {
      window.uploader.upload(this, null, 1);
    },

    placeFile(file) {
      this.preview = file;
      this.course.course_meta.preview = file.id
    },

    save() {
      this.$store.dispatch('storeCourse', this.course).then((resp) => {
        window.location.href = '/courses'
      })
    },

    copyText(value) {
      const el = document.createElement('textarea');
      el.value = value;
      el.setAttribute('readonly', '');
      el.style.position = 'absolute';
      el.style.left = '-9999px';
      document.body.append(el);

      if (navigator.userAgent.match(/Mac|iPhone|iPod|iPad/i)) {
        navigator.clipboard
            .writeText(value)
            .then(() => {
              console.log('success coppy');
            })
            .catch(() => {
              console.log('error coppy');
            });
      } else {
        el.select();
      }
      document.execCommand('copy');
      document.body.removeChild(el);

      this.isCopied = true;

      setTimeout(() => {
        this.isCopied = false;
      }, 5000);
    },

    initQuill() {
      this.quill = new Quill(this.$refs.editor, {
        theme: 'snow',
        bounds: this.$refs.editor,
        scrollingContainer: '.ql-editor-text',
        modules: {
          toolbar: {
            container: [
              [{'header': [2, 3, 4, false]}], // заголовки
              ['bold', 'italic', 'underline'], // жирный, курсив, подчеркнутый
              [{'list': 'ordered'}, {'list': 'bullet'}], // списки нумерованный, точечный
              ['link'], // ссылка
            ],
          }
        },
      });

      if (this.course.course_meta.payment_description) {
        this.quill.root.innerHTML = this.course.course_meta.payment_description;
      }

      this.quill.on('text-change', (eventName, ...args) => {
        this.course.course_meta.payment_description = this.quill.root.innerHTML;
      });
    }
  }
}
</script>

<style scoped>
  .course-settings__group {
    flex-direction: row;
    justify-content: start;
  }

  .course-settings__group-item {
    display: flex;
    flex-direction: column-reverse;
  }

  .course-settings__toggle-switch {
    margin-left: 18px;
    padding-top: 20px;
  }

  .course-settings__group-item>input:disabled {
    opacity: 0.4;
  }

  .course-settings__group-item>input:disabled+label {
    opacity: 0.4;
  }

  .course-settings__link-wrapper {
    margin-top: 30px;
  }
</style>