<template>
    <div class="p-4 d-flex flex-column h-100" style="overflow: auto;">
        <h6 class="fs-11 text-muted text-uppercase mb-3 mt-0 fw-bold">FOR THE MONTH OF {{latest.month}}</h6>
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="ri-database-2-line fs-17"></i>
            </div>
            <div class="flex-grow-1 ms-3 overflow-hidden">
                <b-progress class="animated-progress progress-sm mb-2" :max="latest.ongoing">
                    <b-progress-bar :value="latest.received" variant="primary" />
                </b-progress>
                <span class="text-muted fs-12 d-block text-truncate"><b>{{latest.received}}</b> out of <b>{{latest.ongoing}}</b> ongoing scholars received benefits.</span>
            </div>
        </div>
        <hr class="text-muted"/>
        <button @click="openGenerate()" class="btn btn-md btn-light btn-label waves-effect waves-light" type="button"><i class="bx bxs-webcam label-icon align-middle fs-16 me-2"></i> Generate Lists</button>
        <hr class="text-muted"/>
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <div class="avatar-group">
                    <BLink href="javascript: void(0);" v-for="(item, index) of  latest.scholars.data" :key="index"
                        class="avatar-group-item" v-b-tooltip.hover :title="item.name">
                        <div class="avatar-xxs">
                            <img :src="currentUrl+'/imagess/avatars/'+item.avatar" alt="" class="rounded-circle img-fluid" />
                        </div>
                    </BLink>
                    <a class="avatar-group-item" target="_self" style="cursor: default;">
                        <div class="avatar-xxs">
                            <div class="avatar-title rounded-circle bg-light text-primary">+</div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="flex-shrink-0">
                <div class="text-muted">
                    {{latest.scholars.data.length}} Scholars
                </div>
            </div>
        </div>
        <hr class="text-muted"/>
    </div>
</template>

<script>
export default {
    props: ['latest'],
    data(){
        return {
            currentUrl: window.location.origin,
            latest: { pending: [], scholars: {data: []} },
        }
    },
    methods: {
        set(data){
            this.latest = data;
        },
        openGenerate(){
            this.$parent.showPage('generate');
        }
     }
}
</script>