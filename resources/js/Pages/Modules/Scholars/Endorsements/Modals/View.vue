<template>
    <b-modal v-model="showModal" title="View Reason" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered>    
        <ul class="list-unstyled mb-0 vstack gap-3" v-if="user">
            <li>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img :src="currentUrl+'/imagess/avatars/'+user.profile.avatar" alt="" class="avatar-sm rounded">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-14 mb-1 text-primary">{{user.profile.name}}</h6>
                        <span :class="'badge '+user.status.color+' '+user.status.others">{{user.status.name}}</span>
                    </div>
                </div>
            </li>
            <hr class="text-muted mt-0" />
            <div v-if="type == 'Not Avail'" class="mt-n3">
                <div class="alert alert-danger mb-xl-0 text-center" role="alert">Scholar <b>Not Availing</b>. {{user.notavail.reason}}</div>
            </div>
            <div v-if="type == 'Deferment'" class="mt-n3">
                <div class="alert alert-warning mb-xl-0 text-center" role="alert">Scholar on<b> deferment</b>. {{user.deferment.reason}}</div>
            </div>
        </ul>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <!-- <b-button @click="create('ok')" variant="primary" :disabled="form.processing" block>Save</b-button> -->
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            user: {
                status: {},
                address: {
                    info: {}
                },
                profile: {},
            },
            showModal: false,
            type: ''
        }
    },
    methods : {
        show(data,type){
            this.type = type;
            this.user = data;
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        },
    }
}
</script>
