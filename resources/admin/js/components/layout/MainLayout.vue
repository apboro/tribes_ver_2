<template>
    <div class="page">
        <Sidebar v-if="page.layout_sidebar"></Sidebar>
        <Navbar v-if="page.layout_navbar"></Navbar>
        <div class="page-wrapper" :class="{ 'page-wrapper-full': page.layout_wrapper_full }" >
            <div class="container-xl">
                <PageHeader></PageHeader>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    <router-view></router-view>
                </div>
            </div>

            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"><a href="#" class="link-secondary">Documentation</a></li>
                                <li class="list-inline-item"><a href="#" class="link-secondary">License</a></li>
                                <li class="list-inline-item"><a href="#" target="_blank" class="link-secondary" rel="noopener">Source code</a></li>
                                <li class="list-inline-item">
                                    <a href="#" target="_blank" class="link-secondary" rel="noopener">
                                        Sponsor
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Tribes &copy; Administrator
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="link-secondary" rel="noopener">
                                        v 0.0.5
                                    </a>
                                    DNS: {{ dns }}
                                    TCP: {{ tcp }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
import Sidebar from "./../common/Sidebar";
import Navbar from "./../common/Navbar";
import PageHeader from "./../common/PageHeader";
export default {
    name: "MainLayout",
    components:{
        Sidebar,
        Navbar,
        PageHeader
    },
    computed : {
        dns:() => {
            return window.performance.timing.domainLookupEnd - window.performance.timing.domainLookupStart;
        },
        tcp:() => {
            return performance.timing.connectEnd - performance.timing.connectStart;
        }
    },
    data() {
        return {
            page:{
                layout_sidebar : false,
                layout_navbar : true,
                layout_hide_topbar : false,
                layout_wrapper_full : false,
            }
        }
    },
}
</script>

<style scoped>

</style>