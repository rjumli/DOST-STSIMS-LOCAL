<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <template v-slot:header>
            <h5 class="modal-title" v-if="type == 'status'">Update Status</h5>
            <h5 class="modal-title" v-else>Send Verification</h5>
            <button @click="hide()" type="button" class="btn-close" aria-label="Close"></button>
        </template>
        <div class="customform">
            <div class="mt-1 mb-n3" v-if="type == 'status'">
                <div class="avatar-md mx-auto">
                    <div class="avatar-title rounded-circle bg-light">
                        <span v-if="list.avatar == 'avatar.jpg'" :class="'avatar-title rounded-circle bg-primary text-white'">{{list.profile.lastname[0]}}</span>
                        <img v-else :src="currentUrl+'/imagess/avatars/'+list.avatar" alt="" class="avatar-md rounded-circle">
                    </div>
                </div>
                <div class="p-2 mt-2 text-center">
                    <h6>{{list.profile.name}}</h6>
                    <p class="font-size-12 text-muted" v-if="list.is_active == 1"> This will <span class="text-danger fw-semibold">deactivate</span> the user, restricting their access to the system.</p>
                    <p class="font-size-12 text-muted" v-else> This will <span class="text-success fw-semibold">activate</span> the user, allowing them to access the system. </p>
                </div>
            </div>
            <div class="mt-1 mb-n3" v-else>
                <div class="avatar-md mx-auto">
                    <div class="avatar-title rounded-circle bg-light">
                        <i class="bx bxs-envelope h1 mb-0 text-primary"></i>
                    </div>
                </div>
                <div class="p-2 mt-2 text-center">
                    <h6>Send Verification Link</h6>
                    <p class="font-size-12 text-muted"> Send Verification link to <span class="text-dark fw-semibold">{{list.email}}</span> for activation. </p>
                </div>
            </div>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="update('ok')" variant="primary" :disabled="form.processing" block>
                <span v-if="type == 'status'">Update</span>
                <span v-else>Send</span>
            </b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            type: '',
            list: '',
            form: {},
            showModal: false,
            editable: false,
        }
    },
    methods: {
        show(type,data) {
            this.type = type;
            this.list = data;
            this.showModal = true;
        },
        update(){
            this.form = this.$inertia.form({
                id: this.list.id,
                is_active: (this.list.is_active == 1) ? 0 : 1,
                type: this.type,
                editable: true,
            })
            this.form.put('/staffs/lists/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                },
            });
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>
<style>
.multiselect__single {
    font-size: 12px;
}
</style>