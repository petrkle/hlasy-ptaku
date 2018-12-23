NTAG := $(shell git describe --abbrev=0 | awk '{print $$1"+0.1"}' | bc)

help:
	@echo "help          - napoveda"
	@echo "apk           - vytvori apk soubor"
	@echo "tag           - vytvori novy tag"
	@echo "clean         - smaze generovane a stažene soubory"

apk:
	TERM=xterm-color gradle assembleRelease

tag:
	git tag -a -s -m "Verze $(NTAG)" $(NTAG)

clean:
	TERM=xterm-color gradle clean
	rm -rf build .gradle
	rm -rf tmp
