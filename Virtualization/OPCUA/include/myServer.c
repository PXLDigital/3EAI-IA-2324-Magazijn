#include "server.h"

int main(void) {
    UA_Server *server = UA_Server_new();
    UA_Server_runUntilInterrupt(server);
    UA_Server_delete(server);
    return 0;
}