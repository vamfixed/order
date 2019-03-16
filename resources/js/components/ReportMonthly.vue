<template>
    <div class="">
        <div class="form-group">
            <label for="cboReport">Select Year</label>
            <select v-model="year" class="form-control" @change="update">
                <option :value="m" v-for="(m, index) in years" :key="index">{{ m }}</option>
            </select>
        </div>

        <table class="table table-hover table-striped table-bordered">
            <tr>
                <th>Date</th>
                <th>Amount</th>
            </tr>
            <tr v-for="(order, index) in orders" :key="index">
                <td>{{ order.month }}</td>
                <td>{{ order.total }}</td>
            </tr>
            <tr>
                <td>Total</td>
                <td>{{ total }}</td>
            </tr>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                year: null,
                orders: null,
                years: []
            }
        },
        computed: {
            total() {
                var t = 0;
                if (this.orders) {
                    this.orders.forEach(element => {
                        t += parseFloat(element.total);
                    });
                }
                return t.toFixed(2);
            }
        },
        methods: {
            update() {
                this.$Progress.start();
                axios.get('/api/report/monthly/' + this.year)
                .then(res => {
                    this.orders = res.data;
                    this.$Progress.finish();
                })
                .catch(err => {
                    console.log(err);
                    this.$Progress.fail();
                })
            }
        },
        created() {
            axios.post('/api/report/getYear')
            .then(res => {
                this.years = res.data;
            })
            .catch(error => {
                console.log(error);
            });
        },
    }

</script>
