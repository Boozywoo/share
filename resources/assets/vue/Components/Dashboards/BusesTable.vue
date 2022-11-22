<template>
  <div class="ibox">
    <div class="ibox-content">
      <div class="row">
        <div class="col-md-9">
          <h2>{{ 'admin.dashboards.buses.title' | trans }}</h2>
        </div>
        <div class="col-md-3" style="text-align: right;">
          <div style=" font-size: 25px; cursor: pointer; display: inline-block;"
               data-toggle="modal"
               @click="showFilter = true"
               data-target="#db_bus-filter">
            <i class="fa fa-filter"></i>
          </div>

        </div>
      </div>
      <div class="hr-line-dashed"></div>

      <div id="dashboard-table-parent">
        <div class="scroll-top">
          <div class="scroll-area"></div>
        </div>

        <div class="table-responsive" style="height: 1000px">
          <table class="table table-condensed table-hover" id="dashboard-table">
            <thead>
            <tr>
              <th class="dashboard__table-column"
                  :style="'width: '+columnDefaultWidth+'px; min-width: '+columnMinWidth+'px;'">
                <div class="dashboard__table-column-area">
                  <div class="dashboard__table-column-title" @click="sorting('id','asc')">#
                    <div v-if="sortedField && sortedField.field == 'id'" style="color: yellow">
                      <i class="fa fa-arrow-down" v-if="sortedField.type == 'asc'"></i>
                      <i class="fa fa-arrow-up" v-else></i>
                    </div>
                  </div>
                  <div class="border-line" @mousedown="startResizing($event, 'id')"></div>
                </div>
              </th>
              <th class="dashboard__table-column"
                  :style="'width: '+columnDefaultWidth+'px; min-width: '+columnMinWidth+'px;' + (filters[field] ? 'color:  yellow' : '')"
                  :id="'column_'+field"
                  v-for="field in checkedFields"
                  :key="'field_'+field">
                <div class="dashboard__table-column-area">
                  <div class="dashboard__table-column-title" @click="sorting(field,'asc')">
                    {{ 'admin_labels.' + field | trans }}
                    <div v-if="sortedField && sortedField.field == field" style="color: yellow">
                      <i class="fa fa-arrow-down" v-if="sortedField.type == 'asc'"></i>
                      <i class="fa fa-arrow-up" v-else></i>
                    </div>
                  </div>
                  <div class="border-line" @mousedown="startResizing($event, field)"></div>
                </div>
              </th>
            </tr>
            </thead>
            <tbody v-show="showedTable">
            <span v-show="false">{{ n = 0 }}</span>
            <tr v-for="bus in filteredBuses" :key="bus.id" :class="(n%2 == 0) ? 'second-line': ''">
              <span v-show="false">{{ n = n + 1 }}{{ n >= filteredBuses.length ? showTable() : null }}</span>
              <td>
                <div>{{ bus.id }}</div>
              </td>
              <td v-for="field in checkedFields" :key="bus.id+'_'+field">
                <div class="field-value" v-html="getFieldData(field, bus[field])">
                </div>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="ibox-footer">

    </div>
    <div v-if="showFilter">
      <!--      <div class="modal inmodal in" id="db_bus-filter" tabindex="-1" role="dialog" aria-hidden="true">-->
      <div class="modal inmodal in" tabindex="-1" role="dialog" style="display: block" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content-body" style="min-height: 400px">
            <div class="" style="position: relative; padding: 10px 25px;">
              <button type="button" class="close" @click="showFilter = false" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
              </button>
              <div class="row">
                <div class="col-sm-6">
                  <h2>{{ 'admin_labels.filter' | trans }}</h2>
                </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="row" style="padding: 0 14px;">
                <div class="col-3">
                  <div>
                    <input type="checkbox" id="check-all-fields" value="1" class="good-checkbox"
                           v-model="checkedAll"
                           name="check-all-fields"/>
                    <label for="check-all-fields">Check all</label>
                  </div>
                </div>
                <div class="col-9" style="text-align: right">
                  <button class="btn btn-default" @click="clearFilter">
                    <span class="fa fa-ban"></span> {{ 'admin.filter.drop' | trans }}
                  </button>
                  <button class="btn btn-primary" @click="filtering()"
                          type="button">{{ 'admin.filter.save' | trans }}
                  </button>

                </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="row " v-for="(data, field) in filtersData" :key="'filter_'+field" style="margin-bottom: 10px">

                <div class="col-sm-5">
                  <input type="checkbox" :id="'check-field-'+field" :value="field" class="good-checkbox"
                         v-model="checkedFields"
                         :name="'check-field-'+field"/>
                  <label :for="'check-field-'+field">
                    {{ 'admin_labels.' + field | trans }}
                  </label>
                </div>
                <div class="col-sm-7 form-group">
                  <v-select :options="data" multiple v-model="filters[field]" :id="'select_filter_'+field"
                            :close-on-select="false"
                            class="select2-block select2-once" style="max-width: 400px;"></v-select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-backdrop in"></div>
    </div>
  </div>
</template>
<script>

const BusesTable = {
  data() {
    return {
      showFilter: false,
      showedTable: false,
      showColumns: false,
      filtersData: {},
      filters: {},
      checkedFields: [],
      filteredBuses: [],
      widthColumns: [],
      sortedField: {
        field: 'id',
        type: 'desc'
      },
      resizing: {
        start: 0,
        finish: 0
      },
      columnMinWidth: 70,
      columnDefaultWidth: 120,
    };
  },
  props: {
    buses: Array,
    fields: Object,
    fieldData: Object
  },
  created() {
    $("body").addClass('mini-navbar');
    this.checkedAll = true;
    this.filteredBuses = this.$onlyData(this.buses);
    this.getValuesFields();
  },
  mounted() {
    document.addEventListener('mousemove', this.processResizing);
    document.addEventListener('mouseup', this.endResizing);

  },
  computed: {
    checkedAll: {
      get: function () {
        return this.fields && this.fields.all && (this.fields.all.length == this.checkedFields.length) ? true : false;
      },
      set: function (val) {
        console.log(val);
        if (val) {
          this.checkedFields = this.fields.all;
        } else {
          this.checkedFields = [];
        }
      }
    }
  },
  methods: {
    showTable() {
      this.showedTable = true;
      let screenHeight = $(document).clientHeight + 'px';
      $("#dashboard-table-parent .table-responsive").height(screenHeight);
      this.createScrollBar();
    },
    getFieldData(field, value) {
      if (this.fields.dates.includes(field) && value) {
        let date = new Date(value);

        let day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();
        let month = date.getMonth() + 1;
        month = month < 10 ? '0' + month : month;

        return day + '.' + month + '.' + date.getFullYear();
      }
      if (value) {

        switch (field) {
          case 'status':
            return this.$lang.has('pretty.statuses.' + value) ? this.$lang.get('pretty.statuses.' + value) : '';
          case 'location_status':
            return this.$lang.has('admin.buses.location_statuses.' + value) ? this.$lang.get('admin.buses.location_statuses.' + value) : '';
          case 'color':
            return this.fieldData.colors && this.fieldData.colors[value] ? this.fieldData.colors[value] : '';
          case 'departments':
            return Array.isArray(value) ? value.map(e => e.name).join(', ') : '';
          case 'bus_drivers':
            return Array.isArray(value) ? value.map(e => e.name).join(', <br> ') : '';
          case 'type':
            return value.name ? value.name : '';
          case 'company':
            return value.name ? value.name : '';
          case 'tires':
            return this.$lang.has('admin.buses.tires.' + value) ? this.$lang.get('admin.buses.tires.' + value) : '';
          case 'customer_director':
            return this.fieldData.customerPersonalities && this.fieldData.customerPersonalities[value] ? this.fieldData.customerPersonalities[value] : '';
          case 'customer_company':
            return this.fieldData.customerCompanies && this.fieldData.customerCompanies[value] ? this.fieldData.customerCompanies[value] : '';
          case 'customer_department':
            return this.fieldData.customerDepartments && this.fieldData.customerDepartments[value] ? this.fieldData.customerDepartments[value] : '';
          case 'fact_referral':
            return this.fieldData.customerDepartments && this.fieldData.customerDepartments[value] ? this.fieldData.customerDepartments[value] : '';

          default:
            return value;
        }
      } else {
        return '';
      }

    },
    getValuesFields() {
      if (this.fields && this.fields.all) {
        this.fields.all.map((field) => {
          let dataArray = [];
          switch (field) {
            case 'status':
              dataArray = this.fieldData.statuses ? this.fieldData.statuses : [];
              break;
            case 'location_status':
              dataArray = this.fieldData.location_statuses ? this.fieldData.location_statuses : [];
              break;
            case 'color':
              dataArray = this.fieldData.colors ? this.fieldData.colors : [];
              break;
            case 'departments':
              dataArray = this.fieldData.departments ? this.fieldData.departments : [];
              break;
            case 'bus_drivers':
              dataArray = this.fieldData.busDrivers ? this.fieldData.busDrivers : [];
              break;
            case 'type':
              dataArray = this.fieldData.types ? this.fieldData.types : [];
              break;
            case 'company':
              dataArray = this.fieldData.companies ? this.fieldData.companies : [];
              break;
            case 'tires':
              dataArray = this.fieldData.tires ? this.fieldData.tires : [];
              break;
            case 'customer_director':
              dataArray = this.fieldData.customerPersonalities ? this.fieldData.customerPersonalities : [];
              break;
            case 'customer_company':
              dataArray = this.fieldData.customerCompanies ? this.fieldData.customerCompanies : [];
              break;
            case 'customer_department':
              dataArray = this.fieldData.customerDepartments ? this.fieldData.customerDepartments : [];
              break;
            case 'fact_referral':
              dataArray = this.fieldData.customerDepartments ? this.fieldData.customerDepartments : [];
              break;
            default:
              this.buses.forEach((bus) => {
                let value = bus[field];
                dataArray[value] = value;
              });
          }
          this.filtersData[field] = this.getValidDataForSelect(dataArray);
          dataArray = null;
        });
      }
      console.log(this.filtersData);
    },
    getValidDataForSelect(obj, onlyValue = false) {
      let result = [];
      for (const [key, value] of Object.entries(obj)) {
        if (value !== '' && value !== null) {
          result.push({code: key, label: value})
        }
      }
      // console.log(result);
      return result;
    },
    consoles(data) {
      console.log(data);
    },
    filtering() {

      this.filteredBuses = this.buses;
      if (this.filters && this.filters.length <= 0) {
        this.showFilter = false;
        return;
      }
      this.filteredBuses = this.buses.filter(bus => {
        let result = true;
        for (var [key, filter] of Object.entries(this.filters)) {
          if (!filter || filter.length <= 0) {
            delete this.filters[key];
            return;
          }
          let filter_result = false;
          filter.map(value => {
            if (key == "departments" || key == "bus_drivers") {
              bus[key].map(item => {
                if (item.id == value.code) {
                  filter_result = true;
                }
              });
              return;
            }
            if (this.fields.relations && this.fields.relations.indexOf(key) >= 0) {
              if (bus[key].id && bus[key].id == value.code) {
                filter_result = true;
              }
              return;
            }
            if (bus[key] == value.code) {
              filter_result = true;
            }
          });
          if (!filter_result) {
            result = false;
          }
        }
        return result;
      });
      this.showFilter = false;
      return;
    },
    sorting(field) {
      this.sortedField.field = field;
      if (this.sortedField.type) {
        this.sortedField.type = this.sortedField.type == 'asc' ? 'desc' : 'asc';
      } else {
        this.sortedField.type = 'asc';
      }
      this.filteredBuses = this.filteredBuses.sort(this.sortArray);
      console.log(field);
    },
    clearFilter() {
      this.filters = {};
      this.filtering();
    },
    sortArray(prev, next) {
      let field = this.sortedField.field;
      let type = this.sortedField.type;
      let prev_value = (prev[field] && prev[field] instanceof Array) ? prev[field][0] : prev[field];
      let next_value = (next[field] && next[field] instanceof Array) ? next[field][0] : next[field];
      prev_value = prev_value instanceof Object ? prev_value.name : prev_value;
      next_value = next_value instanceof Object ? next_value.name : next_value;


      if (type == 'asc') {
        if (typeof prev_value == 'number') {
          if (prev_value > next_value) {
            return -1;
          }
          if (prev_value < next_value) {
            return 1;
          }
        } else {
          return prev_value ? prev_value.localeCompare(next_value) : 1;
        }

      } else {
        if (typeof next_value == 'number') {
          if (prev_value < next_value) {
            return -1;
          }
          if (prev_value > next_value) {
            return 1;
          }
        } else {
          return next_value ? next_value.localeCompare(prev_value) : -1;
        }
      }
      return 0;

    },
    startResizing(e, field) {

      this.resizing.field = field;
      this.resizing.start = e.pageX;
      // let elem = $("#column_" + this.resizing.field + " .dashboard__table-column-area");
      let elem = $("#column_" + this.resizing.field);

      this.resizing.start_with = elem.width();
      this.resizing.min_with = elem.parent().width();
      this.resizing.element = elem;
    },
    processResizing(e) {
      if (this.resizing.field && this.resizing.start_with && this.resizing.element) {
        this.resizing.finish = e.pageX;
        let difference = this.resizing.finish - this.resizing.start;
        let current_width = this.resizing.start_with;
        let new_width = current_width + difference;
        if (new_width <= this.columnMinWidth) {
          return;
        }
        this.resizing.element.width(new_width);
      }
    },
    endResizing() {
      this.resizing.field = null;
      this.resizing.start_with = 0;
      this.resizing.start = 0;
      this.resizing.finish = 0;
    },
    createScrollBar() {
      let width = $("#dashboard-table").width() + 'px';
      $("#dashboard-table-parent .scroll-area").attr('style', 'width:' + width);

      console.log(width);
      let el1 = $("#dashboard-table-parent .scroll-top");
      let el2 = $("#dashboard-table-parent .table-responsive");
      el1.scroll(function () {
        el2.scrollLeft(el1.scrollLeft());
      });
      el2.scroll(function () {
        el1.scrollLeft(el2.scrollLeft());
      });
    }
  }
}
export default BusesTable;

function onlyUnique(value, index, self) {
  return self.indexOf(value) === index;
}

</script>
