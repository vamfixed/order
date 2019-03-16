<template>
    <div>
        <div class="form-group">
            <label for="cboReport">Please select report type</label>
            <select v-model="report_type_selected" class="form-control" name="cboReport" id="cboReport" @change="updateComponent">
                <option :value="item.key" v-for="(item, index) in report_type" :key="index">{{ item.value }}</option>
            </select>

            <keep-alive>
                <component :is="componentiD"></component>
            </keep-alive>
        </div>
    </div>
</template>
<script>
    import ReportDaily from './ReportDaily';
    import ReportMonthly from './ReportMonthly'
    export default {
        components: {
            ReportDaily,
            ReportMonthly
        },
        methods: {
            updateComponent() {
                this.componentiD = this.report_type_selected;
            }
        },
        data() {
            return {
                report_type_selected: null,
                componentiD: null,
                report_type: [{
                        key: 'report-daily',
                        value: 'Daily'
                    },
                    {
                        key: 'report-monthly',
                        value: 'Monthly'
                    }
                ]
            }
        },

    }

</script>
