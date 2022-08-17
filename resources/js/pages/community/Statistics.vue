<template>
    <div class="community">
        <!-- <div class="container"> -->
            <!-- HEAD -->
            <!-- <header class="community__header">
                <h2
                    class="community__title"
                    title="Tech in UK"
                >
                    Tech in UK
                </h2>

                <tabs-nav class="community__nav" />
            </header> -->

            <!-- Profile -->
            <!-- <transition name="a-community-profile">
                <community-profile v-if="isVisibleProfile" class="community__profile"/>
            </transition>

            <div class="community__profile-btn-wrapper">
                <button
                    class="community__profile-btn"
                    @click="toggleProfileVisibility"
                >
                    {{ isVisibleProfile ? 'Скрыть профиль' : 'Показать профиль' }}
                </button>
            </div> -->

            <!-- Analytics -->
            <community-analytics class="community__tab" />

            <!-- <v-alerts /> -->
        <!-- </div> -->
    </div>
</template>

<script>
    import TabsNav from '../../components/pages/community/TabsNav.vue';
    import CommunityProfile from '../../components/pages/community/CommunityProfile.vue';
    import CommunityAnalytics from './analytics/CommunityAnalytics.vue';
    import VAlerts from '../../components/ui/alert/VAlerts.vue';

    export default {
        name: 'Statistics',
        
        components: {
            TabsNav,
            CommunityProfile,
            CommunityAnalytics,
            VAlerts,
        },

        data() {
            return {
                isVisibleProfile: this.getProfileVisibilityValue(),
            }
        },

        methods: {
            getProfileVisibilityValue() {
                return localStorage.getItem('is_visible_community_profile') ? (localStorage.getItem('is_visible_community_profile') === 'true') : true;
            },

            toggleProfileVisibility() {
                this.isVisibleProfile = !this.isVisibleProfile;
                localStorage.setItem("is_visible_community_profile", this.isVisibleProfile);
            },
        },
    }
</script>
