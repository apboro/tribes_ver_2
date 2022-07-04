<template>
    <button>
        {{ button.text }}
    </button>
</template>

<script>
export default {
    name: "Button",
    data() {
        return {
            button:{
                text:'Кнопка'
            }
        }
    },
}
</script>

<style scoped>

</style>