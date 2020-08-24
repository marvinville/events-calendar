<template>
    <div>
        <div class="card">
            <h5 class="card-header">Calendar</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <form @submit="handleSave">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputEvent">Event</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder=""
                                        v-model="form.eventName"
                                        name="inputEvent"
                                        id="inputEvent"
                                    />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputFrom">From</label>
                                    <datepicker
                                        v-model="form.dateFrom"
                                        id="inputFrom"
                                        name="inputFrom"
                                        :bootstrap-styling="true"
                                        :format="dateFormatter"
                                        :required="true"
                                    ></datepicker>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputTo">To</label>
                                    <datepicker
                                        v-model="form.dateTo"
                                        id="inputTo"
                                        name="inputTo"
                                        :bootstrap-styling="true"
                                        :format="dateFormatter"
                                        :required="true"
                                    ></datepicker>
                                </div>
                                <div class="form-group col-md-12">
                                    <div
                                        class="form-check form-check-inline"
                                        v-for="day in days"
                                        :key="day.index"
                                    >
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            :id="day"
                                            :value="day"
                                            v-model="form.checkedDays"
                                        />
                                        <label
                                            class="form-check-label"
                                            :for="day"
                                            >{{ day }}</label
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12 mb-5">
                                <button
                                    type="submit"
                                    class="btn btn-primary float-right"
                                >
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-9">
                        <table
                            class="table"
                            v-for="event in events"
                            :key="event.index"
                        >
                            <thead>
                                <tr>
                                    <th colspan="2">
                                        <h2>{{ event.yearMonth }}</h2>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in event.days"
                                    :key="item.index"
                                    v-bind:class="{
                                        'table-success': item.checked
                                    }"
                                >
                                    <td width="30%">{{ item.day }}</td>
                                    <td width="70%">{{ apiData.eventName }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Datepicker from "vuejs-datepicker";
import moment from "moment";
import axios from "axios";
import toastr from "toastr";
export default {
    name: "Calendar",
    components: {
        Datepicker
    },
    data() {
        return {
            events: [],
            days: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            form: {
                eventName: "",
                dateFrom: "",
                dateTo: "",
                checkedDays: []
            },
            apiData: {}
        };
    },
    created() {
        this.getEvents();
    },
    watch: {
        apiData(data) {
            this.displayCalendar(data);
        }
    },
    methods: {
        async handleSave(event) {
            event.preventDefault();

            const dateFrom = moment(this.form.dateFrom).format("yyyy-MM-DD");
            const dateTo = moment(this.form.dateTo).format("yyyy-MM-DD");

            const diff = this.getDateDiff({
                dateFrom: dateFrom,
                dateTo: dateTo
            });

            try {
                if (diff < 0) {
                    toastr.error("Date To should be later than Date From");
                } else {
                    const postData = {
                        ...this.form,
                        ...{
                            dateFrom: dateFrom,
                            dateTo: dateTo
                        }
                    };

                    const response = await axios.post("/api/events", postData, {
                        headers: { "Content-Type": "application/json" }
                    });

                    this.apiData = postData;

                    toastr.success("Event Successfully Saved!");
                }
            } catch (error) {
                console.error(error);
            }
        },
        async getEvents() {
            try {
                const response = await axios.get("/api/events");
                this.apiData = response.data;
            } catch (error) {
                console.error(error);
            }
        },
        displayCalendar(config) {
            const dateFrom = config.dateFrom ?? "";
            const dateTo = config.dateTo ?? "";

            if (dateFrom && dateTo) {
                const diff = this.getDateDiff({ dateFrom, dateTo });

                let days = [];
                let yearMonth = [];
                let results = [];

                for (let i = 0; i <= diff; i++) {
                    let increment = moment(dateFrom).add(i, "days");
                    let dateText = moment(increment).format("D");
                    let dayText = moment(increment).format("ddd");

                    let checked = config.checkedDays.includes(dayText)
                        ? true
                        : false;

                    let displayDate = {
                        day: `${dateText} ${dayText}`,
                        checked
                    };

                    let distinctYearMonth = moment(increment).format(
                        "MMM yyyy"
                    );

                    let monthYearIndex = results.findIndex(
                        elem => elem.yearMonth === distinctYearMonth
                    );

                    if (monthYearIndex < 0) {
                        results.push({
                            yearMonth: distinctYearMonth,
                            days: [displayDate]
                        });
                    } else {
                        results[monthYearIndex].days.push(displayDate);
                    }
                }
                this.events = results;
            }
        },
        splitDate(date) {
            if (!date) return false;
            const split = date.split("-");
            return moment(split);
        },
        dateFormatter(date) {
            if (!date) return false;
            return moment(date).format("yyyy-MM-DD");
        },
        getDateDiff({ dateFrom, dateTo }) {
            if (dateFrom && dateTo) {
                const arrFrom = this.splitDate(dateFrom);
                const arrTo = this.splitDate(dateTo);

                return arrTo.diff(arrFrom, "days");
            }
            return 0;
        }
    }
};
</script>
