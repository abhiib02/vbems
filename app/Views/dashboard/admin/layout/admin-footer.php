</div>
</section>
</section>
</div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    let empTableConfig = {};
    let deptTableConfig = {};
    let holidayTableConfig = {};
    if (window.innerWidth > 992) {
        empTableConfig = {
            paging: false,
            scrollCollapse: false,
            scrollY: (window.innerHeight - 270) + 'px',
            columnDefs: [{
                targets: 7,
                searchable: false
            }]
        }
        deptTableConfig = {
            paging: false,
            scrollCollapse: false,
            scrollY: (window.innerHeight - 270) + 'px',
        }
        holidayTableConfig = {
            paging: false,
            scrollCollapse: false,
            scrollY: (window.innerHeight - 270) + 'px',
        }
    } else {
        empTableConfig = {
            paging: false,
            scrollCollapse: false,
            scrollY: (window.innerHeight - 357) + 'px',
            columnDefs: [{
                targets: 7,
                searchable: false
            }]
        }
        deptTableConfig = {
            paging: false,
            scrollCollapse: false,
            scrollY: (window.innerHeight - 357) + 'px',
        }
        holidayTableConfig = {
            paging: false,
            scrollCollapse: false,
            scrollY: (window.innerHeight - 357) + 'px',
        }
    }
    $(document).ready(function() {

        $('.employees-table').DataTable(empTableConfig);
        $('.department-table').DataTable(deptTableConfig);
        $('.holiday-table').DataTable(holidayTableConfig);
        $('.leavesRequest-table').DataTable({});

    });
    
</script>