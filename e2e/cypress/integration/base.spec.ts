context('Base test', () => {
    beforeEach(() => {
        cy.visit('http://localhost:8101/')
    })

    it('is wrapping', () => {
        cy.xpath("//*[contains(text(), 'Hello world!')]").should("be.visible");
    })

    it('is translating', () => {
        cy.xpath("//*[contains(text(), 'English text one.')]").should("be.visible");
    })

    it('is switching lang', () => {
        cy.xpath("//a[contains(text(), 'De')]").click();
        cy.xpath("//*[contains(text(), 'Hallo Welt!')]").should("be.visible");
        cy.xpath("//*[contains(text(), 'Deutsch text einz.')]").should("be.visible");
    })
});