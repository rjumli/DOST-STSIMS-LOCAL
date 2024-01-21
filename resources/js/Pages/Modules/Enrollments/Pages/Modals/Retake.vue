<template>
    <b-modal v-model="showModal" title="Retake Subject" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-n1 mt-2">

                        <b-form class="row customform mb-n1 mt-n2 g-2">
                            <!-- <div class="col-lg-12">
                                <div class="form-floating">
                                    <input type="text" v-model="subject" class="form-control"  @keyup="search()" readonly>
                                    <label>RETAKE SUBJECT</label>
                                </div>
                            </div> -->
                            <div class="col-lg-12">
                                <input type="text" class="form-control text-center" placeholder="Search Subject" v-model="subject" @keyup="search()" />
                                <b-form-group class="mt-3 font-size-14">
                                    <b-form-radio @change="log" v-for="list in lists" v-bind:key="list.id" v-model="selectedDefault" class="mb-1" :value="list" plain>
                                        <span class="font-size-13">{{ list.code }} {{(list.is_lab == 0) ? '(Lec)' : '(Lab)' }}</span>
                                        <span class="font-size-6"> / </span>
                                        <span class="font-size-13">{{ list.subject }}</span>
                                    </b-form-radio>
                                </b-form-group>
                            </div>
                        </b-form>


                    </div>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="form.processing" block>Save</b-button>
        </template>
    </b-modal>
</template>

<script>
    export default {
        data() {
            return {
                currentUrl: window.location.origin,
                errors: [],
                subject: '',
                subjects: [],
                lists: [],
                selectedDefault: '',
                showModal: false,
                form: {}
            }
        },

        methods: {
            set(subjects) {
                subjects.forEach((subject) => {
                    if(subject.is_failed){
                        this.subjects.push(subject);
                    }
                });
                this.showModal = true;
            },

            create() {
                this.form = this.$inertia.form({
                    to: this.to,
                    from: this.from,
                    scholar_id: this.scholar_id,
                    type: 'switch'
                })

                this.form.put('/enrollments/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update',response.props.flash.data.data.info)
                        this.hide();
                    }
                });
            },
            search() {
                if (this.subjects.length > 0) {
                    this.lists = this.subjects.filter(x => x.code === this.subject);
                }
            },
            log(arg){
                this.to = arg;
            },
            hide(){
                (this.form.hasOwnProperty('reset') ) ? this.form.reset().clearErrors() : '';
                this.from = '';
                this.to = '';
                this.scholar_id = '';
                this.subject = '';
                this.subjects = [];
                this.lists = [];
                this.showModal = false;
            }
        }
    }
</script>
