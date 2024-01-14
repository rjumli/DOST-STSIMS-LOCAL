<template>
    <b-modal v-model="showModal" title="Update Prospectus Status" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform">
            <div class="row">
                <div class="col-md-12 mt-2 mb-n3" v-if="!prospectus.is_active">
                    <div class="alert alert-success alert-dismissible alert-label-icon rounded-label" role="alert"><i class="ri-error-warning-line label-icon"></i>Make prospectus active</div>
                </div>
                 <div class="col-md-12 mt-2 mb-n3" v-else>
                    <div class="alert alert-warning alert-dismissible alert-label-icon rounded-label" role="alert"><i class="ri-error-warning-line label-icon"></i>Make prospectus inactive </div>
                </div>
                <div class="col-md-12 mt-4 text-center">
                    <p class="fw-semibold">To confirm, type "update-prospectus" in the box below</p>
                    <input type="text" v-model="confirmation" class="form-control mt-n3 text-center">
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="showModal = false" variant="light" block>Cancel</b-button>
            <b-button @click="save('ok')" variant="primary" v-if="confirmation == 'update-prospectus'"  block>Update</b-button>
        </template>
    </b-modal>
</template>

<script>
export default {
    props: ['lists'],
    data() {
        return {
            currentUrl: window.location.origin,
            showModal: false,
            prospectus: '',
            confirmation: ''
        }
    },
    methods: {
        set(data){
            this.prospectus = data;
            this.showModal = true;
        },
        save(){
            this.form = this.$inertia.form({
                id: this.prospectus.id,
                is_active: (this.prospectus.is_active) ? 0 : 1,
                type: 'status'
            })

            this.form.put('/schools/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.showModal = false;
                    this.confirmation = '';
                    this.prospectus = '';
                }
            });
        }
    }
}
</script>
