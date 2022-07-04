import { getBase } from "./base";
import { getPayments } from "./payments";
import { getServiceMessage } from "./service_message";

export const LANGUAGE_EN = {
    base: getBase(),
    service_message: getServiceMessage(),
    payments: getPayments(),
}
