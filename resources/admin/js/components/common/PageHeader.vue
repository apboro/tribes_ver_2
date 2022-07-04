<template>
<!--    {% if page.page-header-file %}-->
<!--    {% include layout/headers/{{ page.page-header-file }}.html %}-->
<!--    {% elsif page-header %}-->
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div v-if="pretitle" class="page-pretitle">
                    {{ this.$route.meta.title }}
                </div>
                <h2 class="page-title">
                    {{ this.$route.meta.tabName }}
                </h2>
                <div class="text-muted mt-1" v-if="description">{{ description }}</div>
            </div>
            <div class="col-12 col-md-auto ms-auto d-print-none" v-if="actions">
                <div class="d-flex">
<!--                    <input type="search" class="form-control d-inline-block w-9 me-3" placeholder="Search user…"/>-->
<!--                    <Button></Button>-->
                </div>
            </div>
        </div>
    </div>
<!--    {% endif %}-->
</template>

<script>
import Icon from "../ui/Icon";
import Button from "../ui/Button";
export default {
    name: "PageHeader",
    components:{
        Icon, Button
    },
    data() {
        return {
            pretitle : "null",
            page_header : "null",
            description : null,
            actions: true,
            class: null,
            icon : null,
        }
    },
}
</script>

<style scoped>

</style>