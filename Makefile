NTAG := $(shell git describe --abbrev=0 | awk '{print $$1"+0.1"}' | bc)

help:
	@echo "help          - nápověda"
	@echo "apk           - vytvoří apk"
	@echo "bundle        - vytvoří bundle"
	@echo "tag           - vytvoří novy tag"
	@echo "fastlane      - nainstaluje fastlane"
	@echo "release       - release do google play"
	@echo "clean         - smaže generované a stažené soubory"

apk:
	gradle assembleRelease

bundle:
	gradle bundle

tag:
	git tag -a -s -m "Verze $(NTAG)" $(NTAG)

clean:
	gradle clean
	rm -rf build .gradle
	rm -rf tmp
	cp -r ../web-archives/hlasy-ptaku tmp

fastlane:
	bundle update

release:
	bundle exec fastlane deploy
