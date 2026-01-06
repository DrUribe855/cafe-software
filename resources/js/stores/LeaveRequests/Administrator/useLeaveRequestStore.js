import { defineStore } from 'pinia';
import axios from 'axios';

export const useLeaveRequestsStore = defineStore("leaveRequest", {
    state: ()=> ({
        requests: [],
        sumRequests: {
            total:    0,
            pendings:  0,
            approved: 0,
            rejected: 0
        }
    }),

    actions: {
        setRequests(requests){
            this.requests = requests ?? [];

        },
        getRequests(){
            return this.requests;
        },
        setSumRequests(data){
            this.sumRequests = {
                total: data.total ?? 0,
                pendings: data.pendings ?? 0,
                approved: data.approved ?? 0,
                rejected: data.rejected ?? 0,
            }
        }
    },
});
