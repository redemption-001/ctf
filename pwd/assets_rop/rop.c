#include <stdio.h>
#include <stdlib.h>
#include <fcntl.h>
#include <unistd.h>

void not_called() {
    char flag[25];
    int fd = open("flag.txt", O_RDONLY);
    read(fd, flag, 25);
    puts(flag);
    close(fd);
}

int say_hello() {
	printf("Hi, what's your name? ");
	fflush(stdout);

	char buffer[128];
	read(STDIN_FILENO, &buffer[0], 256);
	printf("Hello, %s\n", buffer);
}

int main(int argc, char** argv) {
	say_hello();

	return EXIT_SUCCESS;
}
