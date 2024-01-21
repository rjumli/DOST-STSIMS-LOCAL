<template>
     <b-modal v-model="showModal" title="Lock Grades" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row g-1">
                <div class="col-lg-12 mb-n1">
                    <div class="form-floating">
                        <input type="text" :value="selected.semester.academic_year" class="form-control" readonly>
                        <label>Academic Year</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-floating">
                        <input type="text" :value="selected.level.others" class="form-control" readonly>
                        <label>Year Level</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-floating">
                        <input type="text" :value="selected.semester.semester.name" class="form-control" readonly>
                        <label>Semester</label>
                    </div>
                </div>
                <div class="col-lg-12 mt-n2 mb-n2">
                    <hr class="text-muted"/>
                </div>
                <div class="col-md-12" v-if="!selected.is_locked">
                    <div class="alert alert-warning text-center mb-xl-0 fs-12" role="alert"><b>You won't be able to update the grades anymore!</b></div>
                </div>
                <div class="col-md-12" v-else>
                    <div class="alert alert-warning text-center mb-xl-0 fs-12" role="alert"><b>You will be able to update the grades again!</b></div>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="save()" variant="primary" :disabled="form.processing" block>Confirm</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            selected: { semester: { semester: {}}, level: {}},
            form: {},
            lists: []
        }
    },
    methods: {
        set(data,lists){
            this.selected = data;
            this.lists = lists;
            this.showModal = true;
        },
        save(){
            this.form = this.$inertia.form({
                id: this.selected.id,
                is_locked: (this.selected.is_locked) ? 0 : 1,
                type: 'lock',
                lists: this.lists
            })

            this.form.post('/enrollments',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.showModal = false;
                }
            });
        },
        hide() {
            this.showModal = false;
        }
    }
}
</script>