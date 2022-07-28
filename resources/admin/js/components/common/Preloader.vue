<template>
  <transition name="fade">
    <div v-if="show" class="preloader">
      <div class="circles">
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <!-- <div class="circle"></div>
        <div class="circle"></div> -->
      </div>
    </div>
  </transition>
</template>

<script>
    export default {
        name: "Preloader",
        data(){
            return {
                show: true
            }
        },
        mounted(){
            if(Boolean(this.show)) this.showToggle()
        },
        methods: {
            showToggle(){
                setTimeout(() => {
                    this.show = false
                }, 700)
            }
        }
    }
</script>

<style lang="scss" scoped>
.preloader {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: absolute;
    z-index: 9999;
    left: 95%;
    top: 49%;
    // left: 53%;
    // top: 78%;

    .circles {
      display: flex;
        .circle {
            height: .3rem;
            margin: .15rem;
            background-color: #206bc4;
            border-radius: 50%;
            animation-name: scaleIn;
            animation-duration: .7s;
            transform: scale(0);
            width: .3rem;

            &:nth-child(1) {
                animation-delay: 0s
            }
            &:nth-child(2) {
                animation-delay: .1s
            }
            &:nth-child(3) {
                animation-delay: .2s
            }
            // &:nth-child(4) {
            //     animation-delay: .15s
            // }
            // &:nth-child(5) {
            //     animation-delay: .2s
            // }
        }
    }
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 1s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}

@keyframes scaleOut {
    0% { 
        transform: scale(1);
    }
    100% { 
        transform: scale(0);
    }
}

@keyframes scaleIn {
    0% { 
        transform: scale(0); 
    }
    100% { 
        transform: scale(1);
    }
}
</style>

<!-- 
<template>
  <div class="preloader" :style="cssVars">
    <span class="rot-1"></span>
    <span class="rot-2"></span>
    <span class="rot-3"></span>
    <span class="rot-4"></span>
    <span class="rot-5"></span>
    <span class="rot-6"></span>
    <span class="rot-7"></span>
    <span class="rot-8"></span>
  </div>
</template>

<script>
export default {
  name: 'Preloader',
  props: {
    color: {
      type: String,
      default: '#206bc4',
    },
    scale: {
      type: Number,
      default: 1,
    },
  },
  computed: {
    cssVars() {
      return {
        '--color': this.color,
        '--scale': this.scale,
      }
    }
  }
}
</script>

<style scoped>
.preloader {
  position: relative;
  width: 35px;
  height: 35px;
  margin: auto;
  transform: scale(var(--scale));
}
.preloader > span {
  position: absolute;
  background-color: transparent;
  height: 5px;
  width: 5px;
  border-radius: 3px;
  animation-name: f_fadeG;
  animation-duration: 1.2s;
  animation-iteration-count: infinite;
  animation-direction: normal;
}
.rot-1 {
  left: 0;
  top: 13px;
  animation-delay: 0.45s;
}
.rot-2 {
  left: 4px;
  top: 4px;
  animation-delay: 0.6s;
}
.rot-3 {
  left: 13px;
  top: 0;
  animation-delay: 0.75s;
}
.rot-4 {
  right: 4px;
  top: 4px;
  animation-delay: 0.9s;
}
.rot-5 {
  right: 0;
  top: 13px;
  animation-delay: 1.05s;
}
.rot-6 {
  right: 4px;
  bottom: 4px;
  animation-delay: 1.2s;
}
.rot-7 {
  left: 13px;
  bottom: 0;
  animation-delay: 1.35s;
}
.rot-8 {
  left: 4px;
  bottom: 4px;
  animation-delay: 1.5s;
}
@keyframes f_fadeG {
  0% {
    background-color: var(--color);
  }
  100% {
    background-color: transparent;
  }
}
</style> -->