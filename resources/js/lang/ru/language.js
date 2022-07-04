import { getBase } from "./base";
import { getPayments } from "./payments";
import { getServiceMessage } from "./service_message";

export const LANGUAGE_RU = {
    base: getBase(),
    service_message: getServiceMessage(),
    payments: getPayments(),
}
