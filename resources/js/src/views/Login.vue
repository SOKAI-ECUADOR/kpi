<template>
    <div
        class="h-screen flex w-full bg-img vx-row no-gutter items-center justify-center"
        id="page-login"
    >
        <div class="vx-col sm:w-full md:w-full lg:w-3/4 xl:w-1/2 sm:m-0 m-4">
            <vx-card>
                <div slot="no-body" class="full-page-bg-color">
                    <div class="vx-row no-gutter justify-center items-center">
                        <div class="vx-col hidden lg:block lg:w-1/2">
                            <img
                                src="@assets/images/pages/sokai.png"
                                alt="login"
                                class="mx-auto"
                            />
                        </div>
                        <div
                            class="vx-col sm:w-full md:w-full lg:w-1/2 md:w-full d-theme-dark-bg"
                            style="height: 400px;"
                        >
                            <div
                                class="px-8 pt-8 login-tabs-container"
                                style="margin-top: 3rem"
                            >
                                <login-jwt></login-jwt>
                            </div>
                        </div>
                    </div>
                </div>
            </vx-card>
        </div>
    </div>
</template>
<script>
import LoginJwt from "./app/LoginJWT.vue";
export default {
    components: {
        LoginJwt
    }
};
</script>
<style lang="scss">
.login-tabs-container {
    min-height: 505px;
    .con-tab {
        padding-bottom: 14px;
    }
    .con-slot-tabs {
        margin-top: 1rem;
    }
}
</style>
