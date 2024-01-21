<template>

</template>
<script>
export default {
    data(){
        return {
            showModal: false,
            value: '',
            form: {},
            attachments: [],
        }
    },
    methods: {
        set(data){
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