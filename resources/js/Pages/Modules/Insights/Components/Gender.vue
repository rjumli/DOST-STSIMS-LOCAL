<template>
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h5 class="card-title mb-0 flex-grow-1">Gender</h5>
            <div>
                <div class="input-group input-group-sm">
                    <select v-model="scholars" @change="fetch()" class="form-select" id="inputGroupSelect01">
                        <option :value="null">All Scholars</option>
                        <option value="ongoing">Ongoing Scholars</option>
                        <option value="graduated">Graduated Scholars</option>
                    </select>
                    <!-- <b-button @click="openView()" type="button" variant="primary">
                        View
                    </b-button> -->
                </div>
            </div>
        </div>
        <div class="card-body" style="height: 330px;">
            <div class="row mt-4 mb-3">
                <div class="col-md-7">
                    <apexchart ref="realtimeChart" class="apex-charts" type="pie" height="270" :series="g"
                        :options="chartOptions">
                    </apexchart>
                </div>
                <div class="col-md-5">
                    <table class="table mb-1 mt-4" v-if="genders.length > 0">
                        <tbody>
                            <tr>
                                <td>Male :</td>
                                <td>{{genders[0]['total']}}</td>
                            </tr>
                            <tr>
                                <td>Female :</td>
                                <td>{{genders[1]['total']}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            currentUrl: window.location.origin,
            genders: [],
            scholars: null,
            colors: ['bg-primary', 'bg-danger', 'bg-success', 'bg-warning', 'bg-info', 'bg-seconday'],
            series: [],
            chartOptions: {
                chart: {
                    type: 'pie',
                    height: 100,
                },
                labels: ['Male', 'Female'],
                colors: ['#0d68e3', '#e21673'],
                legend: {
                    show: false,
                }
            },
        }
    },
    computed : {
        g(){
            if(this.genders.length > 0){
                return [this.genders[0]['total'],this.genders[1]['total']];
            }else{
                return []
            }
        },
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch() {
            axios.get(this.currentUrl+'/insights', {
                params: {
                    type: 'genders',
                    scholars: this.scholars
                }
            })
            .then(response => {
                this.genders = response.data;
            })
            .catch(err => console.log(err));
        },
    }
}
</script>
