<template>
     <b-modal v-model="showModal" title="Confirm Enrollment" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row g-1">
                <div class="col-lg-12 mb-n1">
                    <div class="form-floating">
                        <input type="text" v-model="ay" class="form-control" readonly>
                        <label>Academic Year</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-floating">
                        <input type="text" v-model="year" class="form-control" readonly>
                        <label>Year Level</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-floating">
                        <input type="text" v-model="semester" class="form-control" readonly>
                        <label>Semester</label>
                    </div>
                </div>
                <div class="col-lg-12 mt-n2">
                    <hr class="text-muted"/>
                </div>
                <div class="col-lg-12 mt-n2">
                    <p class="fs-11 mb-1" :class="[($page.props.errors['files.'+0]) ? 'text-danger' : 'text-muted']">Please attach Certificate of Registration.</p>
                    <input multiple type="file" @change="uploadFieldChange" class="form-control mb-n2" style="width: 100%; height: auto;"/>
                </div>
                <div class="col-lg-12">
                    <hr class="text-muted"/>
                </div>
                <div class="col-md-12 mt-n1 mb-n2">
                    <div class="alert alert-warning text-center mb-xl-0 fs-12" role="alert"><b>Are you sure you want to submit enrollment?</b></div>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="form.processing" block>Confirm</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            showModal: false,
            value: '',
            year: '',
            semester: '',
            ay: '',
            form: {},
            attachments: [],
            errors: []
        }
    },
    methods: {
        set(data,ay,year,semester){
            this.year = year;
            this.ay = ay;
            this.semester = semester;
            this.value = data;
            this.showModal = true;
        },
        uploadFieldChange(e) {
            e.preventDefault();
            var files = e.target.files || e.dataTransfer.files;

            if (!files.length)
                return;
            for (var i = files.length - 1; i >= 0; i--) {
                this.attachments.push(files[i]);
            }
        },
        create() {
            if(this.attachments.length > 0){
                for (var i = this.attachments.length - 1; i >= 0; i--) {
                    this.value.append('files[]', this.attachments[i]);
                }
            }else{
                this.value.append('files[]', []);
            }
            this.$inertia.post('/enrollments', this.value, {
                preserveScroll: true,
                forceFormData: true,
                onSuccess: (response) => {
                    this.showModal = false;
                },
                onError: () => {
                    this.errors = this.$page.props.errors;
                }
            });
        },
        hide() {
            this.showModal = false;
            this.errors = [];
        }
    }
}
</script>