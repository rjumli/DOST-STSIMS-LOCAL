<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" size="xl" class="v-modal-custom" modal-class="zoomIn" centered>
        <template v-slot:header>
            <h5 class="modal-title">Export Scholar</h5>
            <button @click="hide()" type="button" class="btn-close" aria-label="Close"></button>
        </template>
        <div class="row" style="height: calc(100vh - 240px); overflow: auto;">
        
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="form.processing" block>Proceed</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            form: {}
        }
    },
    methods : {
        show(){
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>
