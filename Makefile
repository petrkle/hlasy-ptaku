help:
	@echo "help          - napoveda"
	@echo "apk           - vytvori apk soubor"
	@echo "clean         - smaze generovane a stažene soubory"

apk:
	TERM=xterm-color gradle assembleRelease

clean:
	TERM=xterm-color gradle clean
	rm -rf build .gradle
	rm -rf tmp
