<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" size="lg" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <template v-slot:header>
            <h5 class="modal-title">View Activity</h5>
            <button @click="hide()" type="button" class="btn-close" aria-label="Close"></button>
        </template>
        <div class="table-responsive">
            <table class="table table-nowrap table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr class="fs-11">
                        <th style="width: 30%;" class="text-center">Attribute</th>
                        <th style="width: 35%;" class="text-center">Old</th>
                        <th style="width: 35%;" class="text-center">New</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(list,name) in selected.properties.old" v-bind:key="index">
                        <td class="text-center fw-semibold">{{ name.charAt(0).toUpperCase() + name.slice(1)}}</td>
                        <td class="text-center">{{ list }}</td>
                        <td class="text-center">{{ selected.properties.attributes[name] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            selected: {
                properties: []
            },
            showModal: false,
        }
    },
    methods: {
        set(data) {
            this.selected = data;
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>